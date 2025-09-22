<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fundo;

class FundoController extends Controller
{
    // Retorna todos os fundos
    public function index()
    {
        return response()->json(Fundo::all(), 200);
    }

    // Armazena um novo fundo
    public function store(Request $request)
    {
        $fundo = Fundo::create($request->all());
        return response()->json($fundo, 201);
    }
}
