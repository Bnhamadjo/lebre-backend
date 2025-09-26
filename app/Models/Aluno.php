<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Turma;
use App\Models\Nota;

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
        'documentos_historico',
        'sexo',
        'nacionalidade',
        'tipo_documento'


    ];

    protected $casts = [
        'documentos_historico' => 'array'
    ];


public function propinas()
{
    return $this->hasMany(Propina::class, 'aluno_id');
}


    // Relação com as notas
    public function notas()
    {
        return $this->hasMany(Nota::class);
    }
}