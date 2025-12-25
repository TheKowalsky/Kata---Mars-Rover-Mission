<?php

namespace App\Services\Rover;

/*Funció logica que retorna un "buffer" que mostrara pas per pas el moviment del rover
es fa d'aquesta manera per poder veure de forma "fluida" com es mou el rover, sino sol veuriem el estat inicial i el final.
Aquesta funcio no accedira MAI a la Base de Dades*/
final class RoverEngine
{
    public function run(int $x, int $y, string $direction, string $commands, array $obstacles): array
    {
        $collision = false;
        $dir = strtoupper($direction);                                          // Obtenim la direccio actual (Nord(N), Est(E), Oest(W), Sud(S))
        $commands_array = str_split($commands);                                 // Tranformem el String en un array de caracters per tractar acada caracter per separat
        $direccions = ['N', 'E', 'S', 'W'];                                     // La metodologia utilitzada es la següient --> s'ha utilitzat una mena de vector circular, quan arribem al final del vector tornem a començar directament,
                                                                                // aixi el unic que ha de fer les condicions es sumar o resta aquest valor depenende la direccio i obtenim la nova direccio sense tenir que usar mes logica.
       
        $steps = [];                                                            //L'objectiu es emmagatzema cada moviment del rover, ja que despres es vol fer un frontend que mostri un moviment dinamic d'aquest rover
        $obstacleSet = [];
        foreach ($obstacles as $o) {
            $obstacleSet[$o['x'] . ':' . $o['y']] = true;
        }

        foreach ($commands_array as $c) {                                       // Bucle que tracta cada caracter del array
            // En cas de que collision sigui true vol dir que ens hem topat en un obstacle, aixi que hem de parar el moviment del rover.
            if ($collision){
                return [
                    'end' => ['x' => $x, 'y' => $y, 'direction' => $dir],
                    'steps' => $steps,
                    'status' => 'aborted',
                    'obstacle' => null,
                ];
            }
            if ($c === 'L') {                                                   // En cas L --> girar a esquerra
                $index = array_search($dir, $direccions, true);                 // Obtenim el index
                if ($index === 0){                                              // Com hem de restar 1, en cas de ser el inici del vector hem de situar-nos en la ultima poscio
                    $index = 3; 
                }else{                                                          // En cas contrari sol hem de restar 1 al index
                    $index--;
                }
                $dir = $direccions[$index];                                     // Obtenim la direcció actualitzada
            } elseif ($c === 'R') {                                             // La logica es el mateix que en el cas anterior per ara hem de sumar i en cas d'arribar al final del vector, posar el index a 0
                $index = array_search($dir, $direccions, true);
                if($index === 3){
                    $index = 0;
                }else{
                    $index++;
                }
                $dir = $direccions[$index];
            } elseif ($c === 'F') {                                             // En cas de que l'acció sigui avançar:
                [$x, $y] = $this->finsEndavant($x, $y, $dir, $obstacleSet, $collision);                   // Cridem directament a una funcio, aquesta funcio ens retorna directament la nova posició X i Y
            } else {
                throw new \InvalidArgumentException("Moviment incorrecte: {$c}");   // En cas d'error, tornem el error
            }

            // Guardem snapshot després d'executar la comanda
            $steps[] = [
                'cmd' => $c,
                'x' => $x,
                'y' => $y,
                'direction' => $dir,
            ];
        }
        // Retornem al RoverController totes les dades obtingudes
            return [
                'end' => ['x' => $x, 'y' => $y, 'direction' => $dir],
                'steps' => $steps,
                'status' => 'completed',
                'obstacle' => null,
            ];

    }

    private function finsEndavant(int $x, int $y, string $dir, array $obstacleSet, bool &$collision): array
    {
        // Mirem acada possibilitat, i depenent de com estigui mirant el rover haruem de sumar o restar la X o la Y
        [$nx, $ny] = match ($dir) {
            'N' => [$x, $this->wrap($y + 1)],
            'S' => [$x, $this->wrap($y - 1)],
            'E' => [$this->wrap($x + 1), $y],
            'W' => [$this->wrap($x - 1), $y],
            default => throw new \InvalidArgumentException("Direccions invalides: {$dir}"),
        };
        // En cas que en la  posicio que ens movem hi hague un obstacle el que farem es posar collisiona true i retornar la posicio anterior
        if (isset($obstacleSet["$nx:$ny"])) {
            $collision = true;                  
            return [$x, $y];            
        }
        //En cas contrari, vol dir que podem fer un moviment normal i posarem la variable collision a false i retornarem la següent possicio
        $collision = false;
        return [$nx, $ny];

    }
    // En cas de que el rover surti del terra 200 x 200 el que fem es que torni a entrar pel costat contrari
    private function wrap(int $value): int
    {
        $size = 200;
        $m = $value % $size;
        return $m < 0 ? $m + $size : $m;
    }

}
