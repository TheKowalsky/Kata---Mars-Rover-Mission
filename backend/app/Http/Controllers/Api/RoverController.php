<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Rover\RoverEngine;     // És necesari per enviar les dades a la funcio que tractara les dades del command


class RoverController extends Controller
{
    // S'utilitza dins del testeig per veure que ens retorna les dades correctes del rover que volem
    public function show(Request $request)
    {
        $rover = $request->user()->rover;

        return response()->json([
            'x' => $rover->x,
            'y' => $rover->y,
            'direction' => $rover->direction,
        ]);
    }
    /* Funció POST on el usuari enviara les comandes que vol que faci el seu rover, en aquest cas 
    es comprobara que tota la comanda s'ha introduit correctament*/  
   public function commands(Request $request, RoverEngine $engine)
    {
        // Validació (i aquí és on tu ja fas strtoupper)
        $data = $request->validate([
            'commands' => ['required', 'string'],
        ]);
        // Es possen tota la cadena de comandes en majuscules
        $commands = strtoupper($data['commands']);

        // Bucle que tracta tots els caracters per veure que son caracters valids
        if (!preg_match('/^[FLR]+$/', $commands)) {
            return response()->json([
                'message' => 'La cadena de comandes té valors incorrectes (sol F, L, R).',
            ], 422);
        }

        // Obtenim el rover de l’usuari autenticat
        $rover = $request->user()->rover;

        // Obtenim el estat inicial per després poder-ho veure amb mes detall al Postman
        $start = [
            'x' => (int) $rover->x,
            'y' => (int) $rover->y,
            'direction' => (string) $rover->direction,
        ];
        // Obtenim els obstacles del usuari, per despres enviar-ho a la logica del rover
        $obstacles = $request->user()->obstacles()->get(['x','y'])->toArray();


        // Executem RoverEngine el qual es la funcio amb tota la logica
        $result = $engine->run(
            x: $start['x'],
            y: $start['y'],
            direction: $start['direction'],
            commands: $commands,
            obstacles: $obstacles
        );

        // Mostrem el estat final
        $rover->update($result['end']);

        // Retornem els "steps" al usuari, mes endabant al frontend
        return response()->json([
            'start' => $start,
            'steps' => $result['steps'],
            'end' => $result['end'],
            'status' => $result['status'],
            'obstacle' => $result['obstacle'],
            'commands' => $commands,
        ]);
    }
}
