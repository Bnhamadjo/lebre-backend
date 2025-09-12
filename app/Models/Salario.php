<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salario extends Model
{
    protected $fillable = [
        'funcionario_id',
        'valor',
        'referente_mes',
        'data_pagamento',
        'recibo_id'
    ];

    public function professor()
    {
        return $this->belongsTo(Professor::class, 'funcionario_id');
    }
}
