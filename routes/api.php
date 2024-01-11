<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CarroController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\LocacionController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\ModeloController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});



Route::group([

    'middleware' => 'api',
    'prefix' => 'v1'

], function () {
    Route::apiResource('cliente',ClienteController::class);
    Route::apiResource('carro',CarroController::class);
    Route::apiResource('locacion',LocacionController::class);
    Route::apiResource('marca',MarcaController::class);
    Route::apiResource('modelo',ModeloController::class);

});


Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function () {
    Route::post('login',[AuthController::class ,'login']);
    Route::post('logout',[AuthController::class ,'logout']);
    Route::post('refresh',[AuthController::class ,'refresh']);
    Route::post('me',[AuthController::class ,'me']);

});
