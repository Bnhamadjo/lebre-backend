<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Turma extends Model
{
    use HasFactory;

    // Se a tabela não for "turmas", define explicitamente
    // protected $table = 'turmas';

    // Campos que podem ser preenchidos em massa
    // protected $fillable = ['nome', 'codigo', 'descricao'];

    // Relacionamento: uma turma tem muitos alunos
    public function alunos()
    {
        return $this->hasMany(Aluno::class);
    }
}
