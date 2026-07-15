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
        Schema::create('bulletins', function (Blueprint $table) {
            $table->id();
            $table->string('session');
            $table->decimal('moyenne_generale');
            $table->string('mention');
            $table->date('dateGeneration');
            $table->foreignId('etudiant_id')->constrained('etudiants');
            $table->foreignId('semestre_id')->constrained('semestres');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bulletins');
    }
};
