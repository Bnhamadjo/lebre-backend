<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Despesa extends Model
{
    protected $table = 'despesas';

    protected $fillable = [
        'descricao',
        'valor',
        'categoria',
        'data_registro'
    ];

    protected $casts = [
        'valor' => 'float',
        'data_registro' => 'date',
    ];

    public $timestamps = false;
}