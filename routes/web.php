<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/jobs/create', [HomeController::class, 'create'])
    ->middleware('auth.sanctum.redirect')
    ->name('jobs.create');
