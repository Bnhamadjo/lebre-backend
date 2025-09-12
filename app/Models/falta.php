<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Falta extends Model
{
    protected $fillable = [
        'aluno_id',
        'data_falta',
        'tipo', // justificada ou injustificada
        'observacao'
    ];

    public function aluno()
    {
        return $this->belongsTo(Aluno::class);
    }
}
?>