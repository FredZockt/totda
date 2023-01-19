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
Auth::routes();

Route::get('/', function () {
    return view('/home');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/city', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/inventory', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/map', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/work', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/kingdom', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/settings', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/highscore', [\App\Http\Controllers\HighscoreController::class, 'index']);
