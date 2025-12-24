<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rover extends Model
{
    use HasFactory;

    //Camps a omplir en el moment de crear el rover
    protected $fillable  = [

        'user_id',
        'x',
        'y',
        'direction',

    ];
    // Relació Rover - Usuari
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // Relacio 1 * molts --> Ens serveix per fer tots els moviments
    public function moves()
    {
        return $this->hasMany(RoverMove::class);
    }
}
