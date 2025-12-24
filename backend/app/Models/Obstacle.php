<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obstacle extends Model
{
    use HasFactory;
    // Dades que ha d'introduir el usuari per afegir un nou obstacle
    protected $fillable = ['user_id', 'x', 'y'];
    // Relacio usuari - obstacle
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
