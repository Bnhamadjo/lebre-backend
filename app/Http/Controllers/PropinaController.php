<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Propina;
use App\Models\Aluno; // não esqueça de importar o modelo Aluno
use Illuminate\Support\Facades\Log;

class PropinaController extends Controller
{
    public function pagar(Request $request)
    {
        try {
            // Validação
            $validated = $request->validate([
                'aluno_id' => 'required|exists:alunos,id',
                'valor' => 'required|numeric|min:0',
                'referente_mes' => 'required|string',
                'data_pagamento' => 'required|date',
                'metodo_pagamento' => 'required|string',
            ]);

            // Criação do registro de propina
            $propina = Propina::create($validated);

            // Buscar dados do aluno
            $aluno = Aluno::find($request->aluno_id);

            $propina->load('aluno'); // carrega o relacionamento


            return response()->json([
                'message' => 'Propina paga com sucesso!',
                'recibo' => [
                    'recibo_id' => $propina->id,
                    'nome_aluno' => $aluno->nome_completo ?? 'Aluno não encontrado',
                    'turma' => $aluno->atribuir_turma ?? 'Turma não definida',
                    'valor' => $propina->valor,
                    'data_pagamento' => $propina->data_pagamento,
                    'metodo_pagamento' => $propina->metodo_pagamento,
                    'referente_mes' => $propina->referente_mes,
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error('Erro ao pagar propina: ' . $e->getMessage());
            return response()->json(['error' => 'Erro interno no servidor'], 500);
        }
    }

    public function listarPagas()
{
    $propinas = Propina::with('aluno')
        ->orderBy('data_pagamento', 'desc')
        ->get();

    $lista = $propinas->map(function ($p) {
        return [
            'recibo_id' => $p->id,
            'nome_aluno' => $p->aluno->nome_completo ?? 'Aluno não encontrado',
            'turma' => $p->aluno->atribuir_turma ?? 'Turma não definida',
            'valor' => $p->valor,
            'data_pagamento' => $p->data_pagamento,
            'metodo_pagamento' => $p->metodo_pagamento,
            'referente_mes' => $p->referente_mes,
        ];
    });

    return response()->json($lista);
}

public function listarPorMes(Request $request)
{
    $referente_mes = $request->query('data pagamento'); 

    $propinas = Propina::with('aluno')
        ->whereMonth('data_pagamento', $referente_mes)
        ->orderBy('data_pagamento', 'desc')
        ->get();

    $lista = $propinas->map(function ($p) {
        return [
            'recibo_id' => $p->id,
            'nome_aluno' => $p->aluno->nome_completo ?? 'Aluno não encontrado',
            'turma' => $p->aluno->atribuir_turma ?? 'Turma não definida',
            'valor' => $p->valor,
            'data_pagamento' => $p->data_pagamento,
            'metodo_pagamento' => $p->metodo_pagamento,
            'referente_mes' => $p->referente_mes,
        ];
    });

    return response()->json($lista);
}

public function listarTodos()
{
    return Propina::with('aluno')->orderBy('data_pagamento', 'desc')->get();
}


}
