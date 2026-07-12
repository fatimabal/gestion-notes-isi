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
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->string('typeEvaluation'); 
            $table->date('dateEvaluation'); 
            $table->integer('noteMax'); 
            $table->integer('noteMin'); 
            $table->integer('coefficient');
            $table->foreignId('matiere_id')->constrained('matieres');
 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
