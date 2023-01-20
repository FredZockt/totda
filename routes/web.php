<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController as HomeController;
use App\Http\Controllers\KingdomController as KingdomController;
use App\Http\Controllers\HighscoreController as HighscoreController;
use App\Http\Controllers\InventoryController as InventoryController;
use App\Http\Controllers\JobController as JobController;
use App\Http\Controllers\MapController as MapController;
use App\Http\Controllers\CityController as CityController;

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

Route::get('/styleguide', function () {
    return view('/styleguide');
});

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::middleware(['auth', 'job'])->group(function () {
    Route::get('/city', [CityController::class, 'index'])->name('city.index');

    Route::get('/map', [MapController::class, 'index'])->name('map.index');

    Route::get('/work', [HomeController::class, 'index'])->name('home');

    Route::get('/settings', [HomeController::class, 'index'])->name('home');

    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::post('inventory/sell/{id}', [InventoryController::class, 'sell'])->name('inventory.sell');
    Route::delete('inventory/{id}', [InventoryController::class, 'delete'])->name('inventory.delete');

    Route::get('/kingdom', [KingdomController::class, 'index'])->name('kingdom.index');

    Route::post('walk/{id}', [JobController::class, 'walk'])->name('job.walk');

    Route::get('/highscore', [HighscoreController::class, 'index']);
});

