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
        Schema::create('detail_bulletins', function (Blueprint $table) {
    $table->id();
    $table->foreignId('bulletin_id')->constrained('bulletins')->onDelete('cascade');
    $table->foreignId('ue_id')->constrained('ues')->onDelete('cascade');
    $table->float('moyenne_ue')->default(0);
    $table->integer('credits_obtenus')->default(0);
    $table->boolean('valide')->default(false);
    $table->string('appreciation')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_bulletins');
    }
};
