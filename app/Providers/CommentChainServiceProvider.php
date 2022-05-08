<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\CommentChainService;

class CommentChainServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(CommentChainService::class, function ($app) {
            return new CommentChainService();
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
