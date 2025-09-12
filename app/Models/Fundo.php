<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fundo extends Model
{
       protected $fillable = [
        'valor',
        'origem',
        'data_adicao',
        'descricao'
    ];

}
