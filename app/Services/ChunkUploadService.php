<?php

namespace App\Services;

use App\Contracts\ChunkUploadService as ChunkUploadServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class ChunkUploadService implements ChunkUploadServiceInterface{

    public bool $debug = false;

    public string $tempFolder = '/tmp';

    public string $uploadFolder = '/uploads';

    public bool $deleteTmpFolder = true;

    protected Request $request;

    protected $response;

    protected $params;

    public function setTempFolder($path = '/tmp')
    {
        $this->tempFolder = $path;
    }

    public function setUploadFolder($path = '/uploads')
    {
        $this->uploadFolder = $path;
    }

    public function setDebugMode($isDebug = false)
    {
        $this->debug = $isDebug;
    }

    public function process()
    {
        if (!empty($this->resumableParams())) {
            if (!empty($this->request->file())) {
                return $this->handleChunk();
            } else {
                return $this->handleTestChunk();
            }
        }
    }

    public function handleTestChunk()
    {
        $identifier = $this->resumableParam('identifier');
        $filename = $this->resumableParam('filename');
        $chunkNumber = $this->resumableParam('chunkNumber');

        if (!$this->isChunkUploaded($identifier, $filename, $chunkNumber)) {
            return $this->response = 204;
        } else {
            return $this->response = 200;
        }
    }

    public function handleChunk()
    {
        $file = $this->request->file('file');
        $identifier = $this->resumableParam('identifier');
        $filename = $this->resumableParam('filename');
        $chunkNumber = $this->resumableParam('chunkNumber');
        $chunkSize = $this->resumableParam('chunkSize');
        $totalSize = $this->resumableParam('totalSize');

        if (!$this->isChunkUploaded($identifier, $filename, $chunkNumber)) {
            $chunkFile = $this->tmpChunkDir($identifier) . DIRECTORY_SEPARATOR . $this->tmpChunkFilename($filename, $chunkNumber);
            $this->moveUploadedFile($file->getFileInfo()->getPathname(), $chunkFile);
        }

        if ($this->isFileUploadComplete($filename, $identifier, $chunkSize, $totalSize)) {
            $this->createFileAndDeleteTmp($identifier, $filename);

            return $this->response = 201;
        }

        return $this->response = 200;
    }

    private function createFileAndDeleteTmp($identifier, $filename)
    {
        $chunkFiles = File::files($this->tmpChunkDir($identifier));

        if ($this->createFileFromChunks($chunkFiles, $this->uploadFolder . DIRECTORY_SEPARATOR . $filename)
            && $this->deleteTmpFolder) {

            File::deleteDirectory($this->tmpChunkDir($identifier));
        }
    }

    private function resumableParam($shortName)
    {
        $resumableParams = $this->resumableParams();
        if (!isset($resumableParams['resumable' . ucfirst($shortName)])) {
            return null;
        }

        return $resumableParams['resumable' . ucfirst($shortName)];
    }

    public function resumableParams()
    {
        return $this->request->all();
    }

    public function isFileUploadComplete($filename, $identifier, $chunkSize, $totalSize)
    {
        if ($chunkSize <= 0) {
            return false;
        }

        $numOfChunks = intval($totalSize / $chunkSize) + ($totalSize % $chunkSize == 0 ? 0 : 1);

        for ($i = 1; $i < $numOfChunks; $i++) {
            if (!$this->isChunkUploaded($identifier, $filename, $i)) {
                return false;
            }
        }

        return true;
    }

    public function isChunkUploaded($identifier, $filename, $chunkNumber)
    {
        $path = $this->tmpChunkDir($identifier) . DIRECTORY_SEPARATOR . $this->tmpChunkFilename($filename, $chunkNumber);

        return File::exists($path);
    }

    public function tmpChunkDir($identifier)
    {
        $tmpChunkDir = $this->tempFolder . DIRECTORY_SEPARATOR . $identifier;
        if (!File::exists($tmpChunkDir)) {
            File::makeDirectory($tmpChunkDir);
        }

        return $tmpChunkDir;
    }

    public function tmpChunkFilename($filename, $chunkNumber)
    {
        return $filename . '.part' . $chunkNumber;
    }

    public function createFileFromChunks($chunkFiles, $destFile)
    {
        if($this->debug){
            Log::info('Beginning of create files from chunks');
        }

        natsort($chunkFiles);

        foreach ($chunkFiles as $chunkFile) {
            File::append($destFile, File::get($chunkFile));

            if($this->debug) {
                Log::info('Append chunk file: {chunk_file}', ['chunk_file' => $chunkFile]);
            }
        }

        if($this->debug) {
            Log::info('End of create files from chunks');
        }

        return File::exists($destFile);
    }

    public function moveUploadedFile($file, $destFile)
    {
        if (File::exists($file)) {
            return File::copy($file, $destFile);
        }

        return false;
    }

    public function setRequest(Request $request)
    {
        $this->request = $request;
    }
}
