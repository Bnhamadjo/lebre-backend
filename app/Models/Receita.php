<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receita extends Model
{
    protected $table = 'receitas'; // nome da tabela

    protected $fillable = [
        'descricao',
        'valor',
        'data_registro' // corrigido aqui
    ];
}
