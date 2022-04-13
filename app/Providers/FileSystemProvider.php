<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\FileSystem;

class FileSystemProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(FileSystem::class, function ($app) {
            return new FileSystem();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
