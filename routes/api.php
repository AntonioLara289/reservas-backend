<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\EspacioController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('usuario/registrar', [UsuarioController::class, "crear"]);
Route::post('usuario/login', [UsuarioController::class, "login"]);


// Rutas Protegidas por JWT
Route::middleware('auth:api')->group(function () {
    
    // Esta es la nueva versión de /user para JWT
    Route::get('/user', function (Request $request) {
        return response()->json(auth()->user());
    });
    Route::get('token', [UsuarioController::class, 'getTokenData']);

    
    Route::prefix('reservas')->group(function () {
        Route::controller(ReservaController::class)->group(function () {
            Route::post('crear', 'crear');
            Route::put('actualizar', 'actualizar');
            Route::get('get', 'getReservas');
            Route::get('consultar', 'consultarReservacion');
            Route::get('get/estatus', 'getEstatusReserva');
            Route::put('cambiar-estatus', 'cambiarEstatus');
        });
    });

    Route::prefix('espacios')->group(function () {
        Route::controller(EspacioController::class)->group(function () {
            Route::get('get', 'get');
            Route::post('crear', 'crear');
        });
    });

});