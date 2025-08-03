<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\SearchController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/jobs', [JobController::class, 'index'])->name('api.jobs');
Route::get('/jobs/{id}', [JobController::class, 'show'])->name('api.jobs.show');
Route::post('/jobs', [JobController::class, 'store'])->name('api.jobs.store');
Route::put('/jobs/{id}', [JobController::class, 'update'])->name('api.jobs.update');
Route::delete('/jobs/{id}', [JobController::class, 'destroy'])->name('api.jobs.destroy');

Route::get('/companies', [CompanyController::class, 'index'])->name('api.companies');
Route::get('/companies/{id}', [CompanyController::class, 'show'])->name('api.companies.show');
Route::post('/companies', [CompanyController::class, 'store'])->name('api.companies.store');
Route::put('/companies/{id}', [CompanyController::class, 'update'])->name('api.companies.update');
Route::delete('/companies/{id}', [CompanyController::class, 'destroy'])->name('api.companies.destroy');

Route::get('/search', [SearchController::class, 'search'])->name('api.search');
