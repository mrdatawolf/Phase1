<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::middleware(['auth:sanctum', 'verified'])->get('/storage', function () {
    return view('storage');
})->name('storage');

Route::middleware(['auth:sanctum', 'verified'])->get('/bank', function () {
    return view('bank');
})->name('bank');

Route::middleware(['auth:sanctum', 'verified'])->get('/phase1', function () {
    return view('phase1');
})->name('phase1');

Route::middleware(['auth:sanctum', 'verified'])->get('/forge', function () {
    return view('forge');
})->name('forge');

Route::middleware(['auth:sanctum', 'verified'])->get('/merchant', function () {
    return view('merchant');
})->name('merchant');

Route::middleware(['auth:sanctum', 'verified'])->get('/taipan', function () {
    return view('taipan');
})->name('taipan');

Route::middleware(['auth:sanctum', 'verified'])->get('/kingdom', function () {
    return view('kingdom');
})->name('kingdom');

Route::middleware(['auth:sanctum', 'verified'])->get('/confederation', function () {
    return view('confederation');
})->name('confederation');
