<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [PostController::class, 'home'])->name('home');


// user login/logout and crud operation
Route::group(['prefix' => 'user', 'as' => 'users.', 'middleware' => 'auth'], function () {
    Route::controller(UserController::class)->group(function () {
        Route::get('/list', 'allList')->name('list');
        Route::get('/users/{id}/detail', 'showUserDetail')->name('showuserdetail');
        Route::get('/{id}', 'memberRole')->name('show');//for member role
        Route::get('/{user}/edit', 'edit')->name('edit');
        Route::put('/{user}', 'update')->name('update');
        Route::delete('/{user}', 'destroy')->name('destroy');
    });
});

Route::controller(UserController::class)->group(function () {
    Route::get('/create', 'create')->name('users.create');
    Route::post('users', 'store')->name('users.store');
    Route::get('/login', 'login')->name('login');
    Route::post('/login', 'checklogin')->name('check.login');
    Route::post('/logout', 'logout')->name('logout');
    Route::get('/register', 'register')->name('register');
    Route::get('/forgotpsw', 'passwordreset')->name('password.request');
    Route::post('/emailreset', 'emailreset')->name('password.email');
});

//post crud operations
Route::group(['prefix' => 'post', 'as' => 'posts.'], function () {
    Route::controller(PostController::class)->group(function () {
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/details/{id}', 'showDetails')->name('showdetails');//from home page view
    });
});

Route::group(['prefix' => 'post', 'as' => 'posts.', 'middleware' => 'auth'], function () {
    Route::controller(PostController::class)->group(function () {
        Route::get('/list', 'postList')->name('list');
        Route::get('/{id}', 'show')->name('show');
        Route::get('/{post}/edit', 'edit')->name('edit');
        Route::put('/{post}', 'update')->name('update');
        Route::delete('/{user}', 'destroy')->name('destroy');
    });
});

Route::post('/post/{post}/comment', [CommentController::class, 'store'])
     ->name('comments.store')
     ->middleware('auth'); // only logged-in users can comment

