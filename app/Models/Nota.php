<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Aluno;

class Nota extends Model
{
    protected $fillable = [
        'aluno_id',
        'disciplina',
        'periodo',
        'nota',
        'observacao',
        'ano_letivo'
    ];

    public function aluno()
    {
        return $this->belongsTo(Aluno::class);
    }
}

