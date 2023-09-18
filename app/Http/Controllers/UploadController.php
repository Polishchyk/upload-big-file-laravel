<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contracts\ChunkUploadService;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\File;

class UploadController extends Controller
{
    use ApiResponser;

    private ChunkUploadService $ChunkUploadService;
    private string $tmpPath;
    private string $uploadPath;

    public function __construct(ChunkUploadService $ChunkUploadService)
    {
        $this->ChunkUploadService = $ChunkUploadService;
        $this->tmpPath = storage_path().'/tmp';
        $this->uploadPath = storage_path().'/uploads';
    }

    /**
     * Обробка запитів від клієнта resumable.js
     *
     * @return Response
     */
    public function upload(Request $request)
    {
        if (!File::exists($this->tmpPath)) {
            File::makeDirectory($this->tmpPath);
        }

        if(!File::exists($this->uploadPath)) {
            File::makeDirectory($this->uploadPath);
        }

        $this->ChunkUploadService->setUploadFolder($this->uploadPath);
        $this->ChunkUploadService->setTempFolder($this->tmpPath);
        $this->ChunkUploadService->setDebugMode(true);
        $this->ChunkUploadService->setRequest($request);

        $result = $this->ChunkUploadService->process();

        switch($result) {
            case 200:
            case 201:
                return $this->success(['path' => ''], 'OK', 200);
                break;
            case 204:
                return $this->error('Chunk not found', 204);
                break;
            default:
                return $this->error('An error occurred', 404);
        }
    }
}
