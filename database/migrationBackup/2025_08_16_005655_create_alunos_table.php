<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('alunos', function (Blueprint $table) {
            $table->id();
            $table->string('nome_completo');
            $table->date('data_nascimento');
            $table->string('morada');
            $table->string('encarregado1');
            $table->string('encarregado2')->nullable();
            $table->string('classe_anterior', 50);
            $table->string('classe_atual', 50);
            $table->enum('situacao_escolar', ['Aprovado', 'Reprovado', 'Transferido']);
            $table->string('contato1', 20);
            $table->string('contato2', 20)->nullable();
            $table->text('reparo_especial')->nullable();
            $table->string('atribuir_classe', 50);
            $table->string('atribuir_turma', 10); // exemplo: TA01, TA02

            // 🔹 Novos campos para ficheiros
            $table->string('fotografia')->nullable(); // caminho único da foto
            $table->json('documentos_historico')->nullable(); // array de paths

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alunos');
    }
};
