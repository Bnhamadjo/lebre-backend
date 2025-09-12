<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Propina extends Model
{
    protected $table = 'propinas';

    protected $fillable = [
        'aluno_id',
        'valor',
        'data_pagamento',
        'metodo_pagamento',
        'referente_mes' // campo adicionado corretamente
    ];

    /**
     * Relação com o modelo Aluno
     */
    public function aluno(): BelongsTo
    {
        return $this->belongsTo(Aluno::class);
    }
}
