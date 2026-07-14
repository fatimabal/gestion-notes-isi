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
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->decimal('valeur');
            $table->date('dateSaisie');
            $table->string('session');
            $table->foreignId('etudiant_id')->constrained('etudiants');
            $table->foreignId('enseignant_id')->constrained('enseignants');
            $table->foreignId('matiere_id')->constrained('matieres');
            $table->foreignId('evaluation_id')->constrained('evaluations');
            $table->foreignId('semestre_id')->constrained('semestres');
 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
