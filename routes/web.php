<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::prefix('api')->group(function () {
    Route::get('/jobs', [JobController::class, 'getJobs'])->name('api.jobs');
});
