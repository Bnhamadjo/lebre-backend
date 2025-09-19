<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Professor extends Model
{
    // Define explicitamente o nome da tabela
    protected $table = 'professores';

    // Permite preenchimento em massa dos campos abaixo
    protected $fillable = [
        'nomeCompleto',
        'email',
        'telefone',
        'nivel',
        'numeroDisciplinas',
        'turmasAfetos',
        'periodo',
        'permanente',
        'regime'
    ];

public function salarios()
{
    return $this->hasMany(Salario::class, 'funcionario_id');
}


    public function disciplinas()
{
    return $this->hasMany(Disciplina::class);
}


    // Define tipos automáticos para os campos
    protected $casts = [
        'turmasAfetos' => 'array',
        'permanente' => 'boolean',
        'numeroDisciplinas' => 'integer'
    ];
}