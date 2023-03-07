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

Route::get('/styleguide', function () {
    return view('/styleguide');
});

Route::middleware(['auth', 'job', 'sidebar'])->group(function () {
    Route::get('/city', [CityController::class, 'index'])->name('city.index');
    Route::post('/building/sell/{building_id}', [CityController::class, 'sellBuilding'])->name('building.sell');
    Route::post('/building/level/{building_id}', [CityController::class, 'levelUp'])->name('building.level');
    Route::post('/city/apply', [CityController::class, 'apply'])->name('city.apply');
    Route::post('/city/auction', [CityController::class, 'placeBid'])->name('city.auction');
    Route::post('/city/abdicate', [CityController::class, 'abdicate'])->name('city.abdicate');
    Route::post('/city/apply/cancel', [CityController::class, 'cancel'])->name('city.apply.cancel');
    Route::post('/city/apply/tax', [CityController::class, 'tax'])->name('city.apply.tax');
    Route::post('/city/build', [CityController::class, 'build'])->name('city.build');
    Route::post('/city/depose/{city_id}', [CityController::class, 'depose'])->name('city.depose');
    Route::post('/city/appoint/{city_id}/{user_id}', [CityController::class, 'appoint'])->name('city.appoint');
    Route::post('/city/hire', [CityController::class, 'hire'])->name('city.hire');

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
    Route::post('/kingdom/apply', [KingdomController::class, 'apply'])->name('kingdom.apply');
    Route::post('/kingdom/abdicate', [KingdomController::class, 'abdicate'])->name('kingdom.abdicate');
    Route::post('/kingdom/vote/{applicant_id}', [KingdomController::class, 'vote'])->name('kingdom.vote');
    Route::post('/kingdom/apply/cancel', [KingdomController::class, 'cancel'])->name('kingdom.apply.cancel');
    Route::post('/kingdom/hire', [KingdomController::class, 'hire'])->name('kingdom.hire');

    Route::post('walk/{id}', [JobController::class, 'walk'])->name('job.walk');

    Route::get('/highscore', [HighscoreController::class, 'index']);

    Route::get('/search', [SearchController::class, 'index'])->name('search.index');
    Route::post('/search/do', [SearchController::class, 'search']);

    Route::get('/market', [MarketController::class, 'index'])->name('market.index');
    Route::post('/market/buy/{id}', [MarketController::class, 'buy'])->name('market.buy');
});

Route::get('/{any}', function() {
    return redirect('/');
});
