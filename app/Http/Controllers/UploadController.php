<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contracts\ChunkUploadService;
use Illuminate\Support\Facades\File;

class UploadController extends Controller
{

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

        return $this->ChunkUploadService->process();
    }
}
