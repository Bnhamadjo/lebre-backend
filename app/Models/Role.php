<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
Role::insert([
    ['name' => 'admin'],
    ['name' => 'professor'],
    ['name' => 'aluno'],
    ['name' => 'funcionario'],
]);

class Role extends Model
{
    //
}
