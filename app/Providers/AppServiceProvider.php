<?php

namespace App\Providers;

use App\Contracts\Dao\PostDaoInterface;
use App\Contracts\Dao\UserDaoInterface;
use App\Contracts\Services\PostServiceInterface;
use App\Contracts\Services\UserServiceInterface;
use App\Dao\PostDao;
use App\Dao\UserDao;
use App\Services\PostService;
use App\Services\UserService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserServiceInterface::class, UserService::class);
        $this->app->bind(UserDaoInterface::class, UserDao::class);
        $this->app->bind(PostServiceInterface::class, PostService::class);
        $this->app->bind(PostDaoInterface::class, PostDao::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
