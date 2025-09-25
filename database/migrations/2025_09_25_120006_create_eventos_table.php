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
Schema::create('eventos', function (Blueprint $table) {
    $table->id();
    $table->string('titulo');
    $table->text('descricao')->nullable();
    $table->dateTime('inicio');
    $table->dateTime('fim')->nullable();
    $table->string('tipo')->nullable(); // exame, reunião, feriado, etc.
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eventos');
    }
};
