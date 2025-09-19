<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfiguracaoSistema extends Model
{
    protected $table = 'configuracoes_sistema'; // opcional, se quiser controlar o nome

    protected $fillable = [
        'nome_escola',
        'logotipo',
        'cor_sidebar',
        'cor_fundo',
        'cor_botao',
        'tema',
        'idioma',
        'formato_data',
    ];
}
