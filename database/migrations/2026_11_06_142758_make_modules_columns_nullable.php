<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('modules', function (Blueprint $table) {
            $table->integer('credits')->nullable()->change();
            $table->integer('volumeHoraire')->nullable()->change();
            $table->integer('coefficient')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('modules', function (Blueprint $table) {
            $table->integer('credits')->nullable(false)->change();
            $table->integer('volumeHoraire')->nullable(false)->change();
            $table->integer('coefficient')->nullable(false)->change();
        });
    }
};