<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\ChunkUploadService as ChunkUploadServiceInterface;
use App\Services\ChunkUploadService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ChunkUploadServiceInterface::class, ChunkUploadService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
