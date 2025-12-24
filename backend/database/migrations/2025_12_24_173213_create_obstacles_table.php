<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('obstacles', function (Blueprint $table) {
            $table->id();
            // Relacionem aquesta taula en el usuari que vulgui crear el obstacle
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // coordenades (0..199). unsigned per evitar negatius
            $table->unsignedSmallInteger('x');
            $table->unsignedSmallInteger('y');
            $table->timestamps();

            // Evitem que el usuari creí el mateix obtacle dos vegades a la mateixa poscio
            $table->unique(['user_id', 'x', 'y']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obstacles');
    }
};
