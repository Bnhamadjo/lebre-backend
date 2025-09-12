<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('professores', function (Blueprint $table) {
            $table->id();
            $table->string('nomeCompleto');
            $table->string('email')->unique();
            $table->string('telefone')->nullable();
            $table->string('nivel');
            $table->integer('numeroDisciplinas')->default(0);
            $table->json('turmasAfetos')->nullable();
            $table->string('periodo');
            $table->boolean('permanente')->default(false);
            $table->string('regime');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('professores');
    }
};
