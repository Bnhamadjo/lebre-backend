<?php

namespace App\Http\Controllers; 
use Illuminate\Http\Request;
use App\Models\Falta;
use App\Models\Aluno;


class FaltaController extends Controller
{
    public function index(Request $request)
    {
        $faltas = Falta::with('aluno')
            ->when($request->filled('aluno_id'), function ($query) use ($request) {
                $query->where('aluno_id', $request->aluno_id);
            })
            ->when($request->filled('turma_id'), function ($query) use ($request) {
                $query->whereHas('aluno', function ($q) use ($request) {
                    $q->where('turma_id', $request->turma_id);
                });
            })
            ->get();

        return response()->json($faltas);
    }

    public function store(Request $request)
    {
        $request->validate([
            'aluno_id' => 'required|exists:alunos,id',
            'data_falta' => 'required|date',
            'turma' => 'required|string',
            'tipo' => 'required|in:justificada,injustificada',
            'observacao' => 'nullable|string',
            'motivo' => 'nullable|string',
        ]);

        $falta = Falta::create($request->all());

        return response()->json($falta, 201);
    }

    
public function buscarPorNome(Request $request)
{
    $nome = $request->query('aluno');

    if (!$nome) {
        return response()->json(['erro' => 'Nome do aluno não fornecido'], 400);
    }

    $aluno = Aluno::where('nome_completo', $nome)->first();

    if (!$aluno) {
        return response()->json(['erro' => 'Aluno não encontrado'], 404);
    }

    $faltas = Falta::where('aluno_id', $aluno->id)->get();

    return response()->json($faltas);
}

}
