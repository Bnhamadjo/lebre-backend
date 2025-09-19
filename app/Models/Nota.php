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

    public function disciplina()
{
    return $this->belongsTo(Disciplina::class);
}


    public function aluno()
    {
        return $this->belongsTo(Aluno::class);

    }

    // Define tipos automáticos para os campos
    protected $casts = [
        'nota' => 'float',
        'ano_letivo' => 'integer',
        'periodo' => 'string',
        'disciplina' => 'string',
        'observacao' => 'string'
    ];

    public function professor()
{
    return $this->belongsTo(Professor::class);
}

}

