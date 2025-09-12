<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Propina;
use App\Models\Salario;
use App\Models\Despesa;
use App\Models\Fundo;
use App\Models\Receita;
use Illuminate\Support\Facades\Log;

class FinanceiroController extends Controller
{
    public function pagarPropina(Request $request)
    {
        $validated = $request->validate([
            'estudante_id' => 'required|integer',
            'valor' => 'required|numeric',
            'data_pagamento' => 'required|date',
            'metodo_pagamento' => 'required|string',
        ]);

        $propina = Propina::create($validated);

        return response()->json([
            'message' => 'Propina paga com sucesso!',
            'data' => $propina
        ], 200);
    }

    public function pagarSalario(Request $request)
    {
        $validated = $request->validate([
            'funcionario_id' => 'required|integer',
            'valor' => 'required|numeric',
            'data_pagamento' => 'required|date',
            'referente_mes' => 'required|string',
        ]);

        $salario = Salario::create($validated);

        return response()->json([
            'message' => 'Salário pago com sucesso!',
            'data' => $salario
        ], 200);
    }

    public function registrarDespesa(Request $request)
    {
        $validated = $request->validate([
            'descricao' => 'required|string',
            'valor' => 'required|numeric',
            'categoria' => 'required|string',
            'data_registro' => 'required|date',
        ]);

        $despesa = Despesa::create($validated);

        return response()->json([
            'message' => 'Despesa registrada com sucesso!',
            'data' => $despesa
        ], 200);
    }

    public function listarDespesas()
    {
        $despesas = Despesa::all();

        return response()->json([
            'message' => 'Lista de despesas recuperada com sucesso!',
            'data' => $despesas
        ], 200);
    }

    public function adicionarFundo(Request $request)
    {
        $validated = $request->validate([
            'valor' => 'required|numeric',
            'origem' => 'required|string',
            'data_adicao' => 'required|date',
        ]);

        $fundo = Fundo::create($validated);

        return response()->json([
            'message' => 'Fundo adicionado com sucesso!',
            'data' => $fundo
        ], 200);
    }

  public function resumoMensal(Request $request)
{
    try {
        $mes = $request->query('mes');
        $ano = $request->query('ano') ?? date('Y');

        if (!$mes || !is_numeric($mes) || $mes < 1 || $mes > 12) {
            return response()->json(['erro' => 'Mês inválido.'], 400);
        }

        Log::info('Consultando propinas...');
        $propinas = Propina::whereMonth('data_pagamento', $mes)
                           ->whereYear('data_pagamento', $ano)
                           ->get();

        Log::info('Consultando salários...');
        $salarios = Salario::whereMonth('data_pagamento', $mes)
                           ->whereYear('data_pagamento', $ano)
                           ->get();

        Log::info('Consultando despesas...');
        $despesas = Despesa::whereMonth('data_registro', $mes)
                           ->whereYear('data_registro', $ano)
                           ->get();

        Log::info('Consultando receitas...');
        $receitas = Receita::whereMonth('data_registro', $mes)
                           ->whereYear('data_registro', $ano)
                           ->get();

        // Se quiser testar fundos novamente, descomente:
        // Log::info('Consultando fundos...');
        // $fundos = Fundo::whereMonth('data_adicao', $mes)
        //                ->whereYear('data_adicao', $ano)
        //                ->get();

        // Cálculo dos totais
        $totalPropinas = $propinas->sum('valor');
        $totalSalarios = $salarios->sum('valor');
        $totalDespesas = $despesas->sum('valor');
        $totalReceitas = $receitas->sum('valor');
        // $totalFundos   = $fundos->sum('valor');

        return response()->json([
            'totais' => [
                'propinas' => $totalPropinas,
                'salarios' => $totalSalarios,
                'despesas' => $totalDespesas,
                'receitas' => $totalReceitas,
                // 'fundos'   => $totalFundos,
            ],
            'detalhes' => [
                'propinas' => $propinas,
                'salarios' => $salarios,
                'despesas' => $despesas,
                'receitas' => $receitas,
                // 'fundos'   => $fundos,
            ]
        ]);
    } catch (\Exception $e) {
        Log::error('Erro no resumo mensal: ' . $e->getMessage());
        return response()->json(['erro' => 'Erro interno no servidor.'], 500);
    }
}
}