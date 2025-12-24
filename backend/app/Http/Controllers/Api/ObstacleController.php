<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Obstacle;
use Illuminate\Http\Request;

class ObstacleController extends Controller
{
    // En cas d'entrar a la ruta del index, el sistema ens retorna un Json amb tots els obstacles dle usuari
    public function index(Request $request)
    {
        return response()->json([
            'obstacles' => $request->user()->obstacles()->get(['id', 'x', 'y']),
        ]);
    }

    // En cas d'accedir a la ruta /store, ens permet crear un obstacle nou
    public function store(Request $request)
    {   
        // En la validació de les dades es molt important tenir en compte que aquest obstacle no pot passar el limite de 200 x 200
        $data = $request->validate([
            'x' => ['required', 'integer', 'min:0', 'max:199'],
            'y' => ['required', 'integer', 'min:0', 'max:199'],
        ]);
        // Es crea el obstacle nou dins de la base de dades
        $obstacle = $request->user()->obstacles()->create($data);
        // Retorna al usuari un JSON que mostra exactament com s'ha creat el obstacle
        return response()->json([
            'message' => 'Obstacle creat',
            'obstacle' => $obstacle->only(['id', 'x', 'y']),
        ], 201);
    }

    // Esborrar obstacle (només si és meu)
    public function destroy(Request $request, Obstacle $obstacle)
    {
        if ($obstacle->user_id !== $request->user()->id) {
            return response()->json(['message' => 'No autoritzat'], 403);
        }

        $obstacle->delete();

        return response()->json(['message' => 'Obstacle eliminat']);
    }
}
