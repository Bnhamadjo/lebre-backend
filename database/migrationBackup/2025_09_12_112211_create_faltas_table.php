<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('faltas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aluno_id')->constrained('alunos')->onDelete('cascade');
            $table->date('data_falta');
            $table->enum('tipo', ['justificada', 'injustificada']);
            $table->text('observacao')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('faltas');
    }
};
