<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;

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
});