<?php

namespace App\Providers;

use App\Services\Interfaces\LogServiceInterface;
use App\Services\Interfaces\ProjectServiceInterface;
use App\Services\Interfaces\UserServiceInterface;
use App\Services\LogService;
use App\Services\ProjectService;
use App\Services\UserService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->singleton(
            LogServiceInterface::class,
            LogService::class
        );

        $this->app->singleton(
            ProjectServiceInterface::class,
            ProjectService::class
        );

        $this->app->singleton(
            UserServiceInterface::class,
            UserService::class
        );
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
