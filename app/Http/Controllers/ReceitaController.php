<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Receita;
use Illuminate\Support\Facades\Log;

class ReceitaController extends Controller
{
    /**
     * Retorna receitas filtradas por mês.
     */
    public function listarPorMes(Request $request)
    {
        try {
            $mes = $request->query('mes');

            if (!$mes || !is_numeric($mes) || $mes < 1 || $mes > 12) {
                return response()->json(['erro' => 'Mês inválido.'], 400);
            }

            $receitas = Receita::whereMonth('data', $mes)->get();

            return response()->json($receitas);
        } catch (\Exception $e) {
            Log::error('Erro ao listar receitas por mês: ' . $e->getMessage());
            return response()->json(['erro' => 'Erro interno no servidor.'], 500);
        }
    }
}
