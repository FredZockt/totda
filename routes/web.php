<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController as HomeController;
use App\Http\Controllers\KingdomController as KingdomController;
use App\Http\Controllers\HighscoreController as HighscoreController;
use App\Http\Controllers\InventoryController as InventoryController;
use App\Http\Controllers\JobController as JobController;
use App\Http\Controllers\MapController as MapController;
use App\Http\Controllers\CityController as CityController;
use App\Http\Controllers\WorkController as WorkController;
use App\Http\Controllers\SearchController as SearchController;
use App\Http\Controllers\MarketController as MarketController;

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
    return view('/welcome');
});


Route::middleware(['auth', 'job', 'sidebar'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::get('/city', [CityController::class, 'index'])->name('city.index');

    Route::get('/map', [MapController::class, 'index'])->name('map.index');

    Route::get('/work', function() {
        return redirect('city')->with([
            'status' => 'Choose a building first',
            'status_type' => 'warning'
        ]);
    });

    Route::get('work/{id}', [WorkController::class, 'index'])->name('work.index');
    Route::post('work/{id}/start/{task}', [WorkController::class, 'start'])->name('work.start');

    Route::get('/settings', [HomeController::class, 'index'])->name('home');

    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::post('inventory/sell/{id}', [InventoryController::class, 'sell'])->name('inventory.sell');
    Route::delete('inventory/{id}', [InventoryController::class, 'delete'])->name('inventory.delete');

    Route::get('/kingdom', [KingdomController::class, 'index'])->name('kingdom.index');

    Route::post('walk/{id}', [JobController::class, 'walk'])->name('job.walk');

    Route::get('/highscore', [HighscoreController::class, 'index']);

    Route::get('/search', [SearchController::class, 'index'])->name('search.index');
    Route::post('/search/do', [SearchController::class, 'search']);

    Route::get('/market', [MarketController::class, 'index'])->name('market.index');
    Route::post('/market/buy/{id}', [MarketController::class, 'buy'])->name('market.buy');
});

