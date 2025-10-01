<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// user login/logout and crud operation

Route::group(['prefix' => 'user', 'as' => 'users.', 'middleware' => 'auth'], function () {
    Route::controller(UserController::class)->group(function () {
        Route::get('/list', 'userList')->name('list');
        Route::get('/{id}', 'show')->name('show');//for member role
        Route::get('/{user}/edit', 'edit')->name('edit');
        Route::put('/{user}', 'update')->name('update');
        Route::delete('/{user}', 'destroy')->name('destroy');
    });
});

Route::controller(UserController::class)->group(function () {
    Route::get('/create', 'create')->name('users.create');
    Route::post('users', 'store')->name('users.store');
    Route::get('/', 'login')->name('login');
    Route::post('/', 'checklogin')->name('check.login');
    Route::post('/logout', 'logout')->name('logout');
    Route::get('/register', 'register')->name('register');
    Route::get('/forgotpsw', 'passwordreset')->name('password.request');
    Route::post('/emailreset', 'emailreset')->name('password.email');
});
