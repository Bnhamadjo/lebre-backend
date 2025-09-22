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
        Schema::create('fundos', function (Blueprint $table) {
            $table->id();
            $table->decimal('valor', 10, 2);
            $table->string('origem'); // 'entrada' ou 'saida'
            $table->date('data_adicao'); // Data do movimento
            $table->string('descricao')->nullable(); // Adicione este campo se quiser salvar a descrição
            $table->timestamps(); // created_at e updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fundos');
    }
};
