<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RoverController;


Route::post('/register', [AuthController::class, 'register']);                  // Enregistrar un nou usuari
Route::post('/login', [AuthController::class, 'login']);                        // Logejar un usuari ja creat
// Rutes protegides
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);                           // Veure el meu propi usuari
    Route::post('/logout', [AuthController::class, 'logout']);                  // Fer un "Logout" del usuari logejat
    Route::get('/rover', [RoverController::class, 'show']);                     // Mostrar Rover del usuari
    Route::post('/rover/commands', [RoverController::class, 'commands']);       // Intruducció de moviments
});
