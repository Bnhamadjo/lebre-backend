<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disciplina extends Model
{
    protected $fillable = ['nome'];


    // Define tipos automáticos para os campos
    protected $casts = [
        'nome' => 'string'
    ];  

    public function professor()
    {
        return $this->belongsTo(Professor::class);
    }
    public function notas()
    {
        return $this->hasMany(Nota::class);
    }
        
}