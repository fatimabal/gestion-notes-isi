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
        Schema::create('deliberations', function (Blueprint $table) {
        $table->id();
        $table->foreignId('classe_id')->constrained('classes');
        $table->foreignId('semestre_id')->constrained('semestres');
        $table->foreignId('validee_par')->constrained('users');
        $table->enum('statut', ['en_attente', 'validee'])->default('en_attente');
        $table->timestamp('date_validation')->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliberations');
    }
};
