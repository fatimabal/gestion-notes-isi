<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::rename('scolarites', 'agent_service_examens');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Dans down() : revenir en arrière
        Schema::rename('agent_service_examens', 'scolarites');

    }
};
