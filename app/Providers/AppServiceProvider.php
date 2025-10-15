<?php

namespace App\Providers;

use App\Contracts\Dao\CommentDaoInterface;
use App\Contracts\Dao\PostDaoInterface;
use App\Contracts\Dao\UserDaoInterface;
use App\Contracts\Services\CommentServiceInterface;
use App\Contracts\Services\PostServiceInterface;
use App\Contracts\Services\UserServiceInterface;
use App\Dao\PostDao;
use App\Dao\UserDao;
use App\Dao\CommentDao;
use App\Services\PostService;
use App\Services\UserService;
use App\Services\CommentService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

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
        $this->app->bind(CommentServiceInterface::class, CommentService::class);
        $this->app->bind(CommentDaoInterface::class, CommentDao::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();//to work paginator properly
    }
}
