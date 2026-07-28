<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'admin.dashboard')->name('dashboard');

Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);

Route::resource('posts', \App\Http\Controllers\Admin\PostController::class);