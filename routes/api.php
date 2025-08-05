<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\Auth\AuthenticationController;

Route::post('/login', [AuthenticationController::class, 'store']);
Route::get('/jobs', [JobController::class, 'index'])->name('api.jobs');
Route::get('/jobs/{id}', [JobController::class, 'show'])->name('api.jobs.show');
Route::get('/companies', [CompanyController::class, 'index'])->name('api.companies');
Route::get('/companies/{id}', [CompanyController::class, 'show'])->name('api.companies.show');
Route::get('/search', [SearchController::class, 'search'])->name('api.search');
Route::post('/search/store', [SearchController::class, 'store'])->name('api.search.store');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', [AuthenticationController::class, 'user']);

    Route::post('/logout', [AuthenticationController::class, 'destroy']);

    Route::post('/jobs', [JobController::class, 'store'])->name('api.jobs.store');
    Route::put('/jobs/{id}', [JobController::class, 'update'])->name('api.jobs.update');
    Route::delete('/jobs/{id}', [JobController::class, 'destroy'])->name('api.jobs.destroy');

    Route::post('/companies', [CompanyController::class, 'store'])->name('api.companies.store');
    Route::put('/companies/{id}', [CompanyController::class, 'update'])->name('api.companies.update');
    Route::delete('/companies/{id}', [CompanyController::class, 'destroy'])->name('api.companies.destroy');
});
