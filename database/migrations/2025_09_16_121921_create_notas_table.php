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
        Schema::create('notas', function (Blueprint $table) {
    $table->id();
    $table->foreignId('aluno_id')->constrained('alunos')->onDelete('cascade');
    $table->string('disciplina');
    $table->string('periodo'); // Ex: "1º", "2º", "3º", "Exame", "Recuperação"
    $table->decimal('nota', 5, 2)->nullable();
    $table->text('observacao')->nullable();
    $table->year('ano_letivo');
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notas');
    }
};
