<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void{
    Schema::create('propinas', function (Blueprint $table) {
    $table->id();
    $table->foreignId('aluno_id')->constrained('alunos')->onDelete('cascade');
    $table->decimal('valor', 10, 2);
    $table->date('data_pagamento');
    $table->string('metodo_pagamento');
    $table->string('referente_mes');
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('propinas');
    }
};
