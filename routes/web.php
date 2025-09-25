<?php

use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// user login/logout and crud operation
Route::controller(UserController::class)->group(function () {
    Route::get('/index', 'index')->name('users.index')->middleware(['auth']);
    Route::get('/create', 'create')->name('users.create');
    Route::post('users', 'store')->name('users.store');
    Route::get('users/{user}/edit', 'edit')->name('users.edit')->middleware(['auth']);
    Route::put('users/{user}', 'update')->name('users.update')->middleware(['auth']);
    Route::delete('users/{user}', 'destroy')->name('users.destroy')->middleware(['auth']);

    Route::get('/', 'login')->name('login');
    Route::post('/', 'checklogin')->name('check.login');
    Route::post('/logout', 'logout')->name('logout');
    Route::get('/register', 'register')->name('register');
    Route::get('/forgotpsw', 'passwordreset')->name('password.request');
    Route::post('/emailreset', 'emailreset')->name('password.email');
});


