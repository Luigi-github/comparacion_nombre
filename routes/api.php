<?php

use App\Http\Controllers\Api\V1\MatchSearchController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(function (){
    // Rutas de autenticación
    Route::prefix('users')->group(function (){
        Route::post('/token', [UserController::class, 'getToken']);
    });

    // Rutas protegidas por autenticación Oauth2.0
    Route::group(['middleware' => ['auth:api']], function (){
        Route::get('/match-names', [MatchSearchController::class, 'getMatchNames']);
        Route::get('/{uuid}/match-names', [MatchSearchController::class, 'getMatchNamesByUUID']);
    });
});
