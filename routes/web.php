<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/jobs/create', [HomeController::class, 'create'])->name('jobs.create');
});
