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
Route::group(['prefix' => 'user', 'as' => 'users.', 'middleware' => 'post'], function () {
    Route::controller(UserController::class)->group(function () {
        Route::get('/list', 'allList')->name('list');
        Route::get('/users/{id}/detail', 'showUserDetail')->name('showuserdetail');
        Route::get('/{id}', 'memberRole')->name('show');//for member role
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
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
        Route::get('/create', 'create')->name('create')->middleware('post');
        Route::post('/store', 'store')->name('store');
        Route::get('/details/{id}', 'showDetails')->name('showdetails')->middleware('post');//from home page view
    });
});

Route::group(['prefix' => 'post', 'as' => 'posts.'], function () {
    Route::controller(PostController::class)->group(function () {
        Route::get('/list', 'postList')->name('list');
        Route::get('/{id}', 'show')->name('show')->middleware('post');
        Route::get('/{post}/edit', 'edit')->name('edit');
        Route::put('/{post}', 'update')->name('update');
        Route::delete('/{user}', 'destroy')->name('destroy');
    });
});

Route::post('/post/{post}/comment', [CommentController::class, 'store'])
     ->name('comments.store')
     ->middleware(['auth']); // only logged-in users can comment

Route::get('/users/download', [UserController::class, 'downloadCSV'])->name('users.download');
Route::post('/users/upload', [UserController::class, 'uploadCSV'])->name('users.upload');
Route::get('/posts/download', [PostController::class, 'downloadCSV'])->name('posts.download');
Route::post('/posts/upload', [PostController::class, 'uploadCSV'])->name('posts.upload');

