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
       Schema::create('configuracoes_sistema', function (Blueprint $table) {
    $table->id();
    $table->string('nome_escola')->nullable();
    $table->string('logotipo')->nullable(); // caminho do arquivo
    $table->string('cor_sidebar')->nullable();
    $table->string('cor_fundo')->nullable();
    $table->string('cor_botao')->nullable();
    $table->string('tema')->default('claro'); // claro ou escuro
    $table->string('idioma')->default('pt');
    $table->string('formato_data')->default('dd/mm/yyyy');
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configuracoes_sistema');
    }
};
