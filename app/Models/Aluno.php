<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Turma;


class Aluno extends Model
{
    protected $table = 'alunos';

    protected $fillable = [
        'nome_completo',
        'data_nascimento',
        'morada',
        'encarregado1',
        'encarregado2',
        'classe_anterior',
        'classe_atual',
        'situacao_escolar',
        'contato1',
        'contato2',
        'reparo_especial',
        'atribuir_classe',
        'atribuir_turma',
        'fotografia',
        'documentos_historico'
    ];

// App\Models\Aluno.php
public function turma()
{
    return $this->belongsTo(Turma::class);
}

    
    protected $casts = [
        'documentos_historico' => 'array'
    ];
}