<?php

use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ThemeController;

Route::middleware('auth')->group(function () {
  Route::get('users', [UserController::class, 'index'])->name('users.index');
});
