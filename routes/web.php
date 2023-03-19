<?php

use App\Modules\SearchCities\src\Controllers\SearchCitiesController;
use App\Modules\Words\src\Controllers\WordsController;
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

Route::get('', function () {
    return view('welcome');
});
Route::get('/addWords', function () {
    return view('welcome');
});
Route::get('/allWords', function () {
    return view('welcome');
});

Route::get('/searchCities', [SearchCitiesController::class, 'searchCity']);

Route::get('/getWords', [WordsController::class, 'getWords']);
Route::get('/getTypes', [WordsController::class, 'getTypes']);
Route::get('/clearProgress', [WordsController::class, 'clearProgress']);
Route::get('/getProgress', [WordsController::class, 'getProgress']);
Route::post('/saveWords', [WordsController::class, 'saveWords']);
Route::post('/saveProgress', [WordsController::class, 'saveProgress']);
Route::post('/deleteWord', [WordsController::class, 'deleteWord']);
Route::put('/saveOneWord', [WordsController::class, 'saveOneWord']);
