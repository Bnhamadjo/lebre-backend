<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Despesa;

class DespesaController extends Controller
{
    public function index()
    {
        try {
            $despesas = Despesa::all();
            return response()->json($despesas, 200);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar despesas: ' . $e->getMessage());
            return response()->json(['error' => 'Erro interno ao buscar despesas'], 500);
        }
    }

    public function listarPorMes(Request $request)
 {
    $mes = $request->query('mes');
    $despesas = Despesa::whereMonth('data', $mes)->get();

    return response()->json($despesas);
 } 
    public function store(Request $request)
    {
        $validated = $request->validate([
            'descricao' => 'required|string|max:255',
            'valor' => 'required|numeric|min:0',
            'data' => 'required|date',
            'categoria' => 'nullable|string|max:100',
        ]);

        try {
            $despesa = Despesa::create($validated);
            return response()->json($despesa, 201);
        } catch (\Exception $e) {
            Log::error('Erro ao criar despesa: ' . $e->getMessage());
            return response()->json(['error' => 'Erro interno ao criar despesa'], 500);
        }
    }

}