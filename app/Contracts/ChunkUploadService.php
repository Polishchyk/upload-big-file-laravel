<?php

namespace App\Contracts;
use Illuminate\Http\Request;

interface ChunkUploadService
{
    public function setTempFolder($path = '/tmp');
    public function setUploadFolder($path = '/uploads');
    public function setDebugMode($isDebug = false);
    public function setRequest(Request $request);
    public function process();
    public function handleChunk();
    public function handleTestChunk();
    public function resumableParams();
    public function isFileUploadComplete($filename, $identifier, $chunkSize, $totalSize);
    public function isChunkUploaded($identifier, $filename, $chunkNumber);
    public function tmpChunkDir($identifier);
    public function tmpChunkFilename($filename, $chunkNumber);
    public function createFileFromChunks($chunkFiles, $destFile);
    public function moveUploadedFile($file, $destFile);
}
