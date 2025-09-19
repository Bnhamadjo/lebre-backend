<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Disciplina;

class DisciplinaController extends Controller
{
public function index()
    {
        return Disciplina::all();
    }

    public function store(Request $request)
    {
        $request->validate(['nome' => 'required|unique:disciplinas']);
        return Disciplina::create($request->all());
    }

}
