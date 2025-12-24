<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RoverController;
use App\Http\Controllers\Api\ObstacleController;



Route::post('/register', [AuthController::class, 'register']);                  // Enregistrar un nou usuari
Route::post('/login', [AuthController::class, 'login']);                        // Logejar un usuari ja creat
// Rutes protegides
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);                           // Veure el meu propi usuari
    Route::post('/logout', [AuthController::class, 'logout']);                  // Fer un "Logout" del usuari logejat
    Route::get('/rover', [RoverController::class, 'show']);                     // Mostrar Rover del usuari
    Route::post('/rover/commands', [RoverController::class, 'commands']);       // Intruducció de moviments
    Route::get('/obstacles', [ObstacleController::class, 'index']);             // Mostra tots els obstacles creats pel usuari
    Route::post('/obstacles', [ObstacleController::class, 'store']);            // Crea un nou obstacle
    Route::delete('/obstacles/{obstacle}', [ObstacleController::class, 'destroy']); // Elimina un obstacle
});
