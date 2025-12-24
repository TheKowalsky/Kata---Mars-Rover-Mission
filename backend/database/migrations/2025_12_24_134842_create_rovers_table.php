<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
// Taula dels rovers creats, l'objectiu es crear un rover per cada usuari registrar,
// Aquest usuari sol podra moure el seu propi rover
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Esquema de la taula de rovers
        Schema::create('rovers', function (Blueprint $table) {
            // ID del rover
            $table->id();
            // Clau secundaria per linkar-ho en la taula dels usuaris
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            // Posicio per defecte del Rover
            $table->integer('x')->default(0);
            $table->integer('y')->default(0);
            // Direccio a la que mira el Rover, per defecte es al Nord(N)
            $table->char('direction', 1)->default('N'); // N,E,S,W

            $table->timestamps();
            // Especfiquem que sol volem un rover per usuari
            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rovers');
    }
};
