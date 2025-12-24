<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
}
