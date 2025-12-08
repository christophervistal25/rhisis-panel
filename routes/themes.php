<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ThemeController;

Route::middleware('auth')->group(function () {
    Route::get('themes', [ThemeController::class, 'index'])->name('themes.index');
});
