<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\InvoiceApiController;
use App\Http\Controllers\Api\InvoiceItemApiController;
use App\Http\Controllers\Api\ContactApiController;
use App\Http\Controllers\Api\ItemApiController;
use App\Http\Controllers\Api\LoginApiController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
/**
 * Ruta Login
 */
Route::post('login', [LoginApiController::class, 'login']);
/**
 * Ruta registro
 */
Route::post('regitro', [LoginApiController::class, 'store']);

Route::middleware(['auth:sanctum'])->group(function () {
    /**
     * Rutas para facturas
     */
    Route::apiResource('factura', InvoiceApiController::class)->only(['index', 'store', 'update', 'destroy', 'show']);
    
    /**
     * Rutas para items de facturas
     */
    Route::apiResource('item-factura', InvoiceItemApiController::class)->only(['destroy']);

    /**
     * Rutas para Contactos (Emisores y Receptores)
     */
    Route::apiResource('contacto', ContactApiController::class)->only(['index']);

    /**
     * Rutas para item
     */
    Route::get('item', [ItemApiController::class, 'index']);

    Route::post('logout', [LoginApiController::class, 'logout']);
});