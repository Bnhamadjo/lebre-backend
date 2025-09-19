<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Aluno;
use App\Models\Turma;

class AlunoController extends Controller
{
    /**
     * Lista todos os alunos
     */
    public function index()
    {
        return response()->json(Aluno::all(), 200);
    }

    /**
     * Cria um novo aluno
     */
    public function store(Request $request)
    {
        Log::info('Dados recebidos (store):', $request->all());

        $validated = $request->validate([
            'nome_completo' => 'required|string|max:255',
            'data_nascimento' => 'required|date',
            'morada' => 'required|string|max:255',
            'encarregado1' => 'required|string|max:255',
            'encarregado2' => 'nullable|string|max:255',
            'classe_anterior' => 'required|string|max:50',
            'classe_atual' => 'required|string|max:50',
            'situacao_escolar' => 'required|in:Aprovado,Reprovado,Transferido',
            'contato1' => 'required|string|max:20',
            'contato2' => 'nullable|string|max:20',
            'reparo_especial' => 'nullable|string',
            'atribuir_classe' => 'required|string|max:50',
            'atribuir_turma' => ['required', 'regex:/^TA[0-9]{1,2}$/'],
            'fotografia' => 'nullable|image|max:2048',
            'documentos_historico' => 'nullable|array',
            'documentos_historico.*' => 'file|max:5120',
        ]);

        if ($request->hasFile('fotografia')) {
            $validated['fotografia'] = $request->file('fotografia')->store('fotografias', 'public');
        }

        if ($request->hasFile('documentos_historico')) {
            $documentosPaths = [];
            foreach ($request->file('documentos_historico') as $documento) {
                $documentosPaths[] = $documento->store('documentos_historico', 'public');
            }
            $validated['documentos_historico'] = json_encode($documentosPaths);
        }

        $aluno = Aluno::create($validated);

        return response()->json($aluno, 201);
    }

    /**
     * Exibe um aluno específico
     */
    public function show($id)
 {
    $aluno = Aluno::find($id);

    if (!$aluno) {
        return response()->json(['erro' => 'Aluno não encontrado'], 404);
     }

     return response()->json($aluno);
  }


    /**
     * Atualiza um aluno existente
     */
    public function update(Request $request, $id)
    {
        Log::info('Dados recebidos (update):', $request->all());

        $aluno = Aluno::find($id);

        if (!$aluno) {
            return response()->json(['erro' => 'Aluno não encontrado'], 404);
        }

        $validated = $request->validate([
            'nome_completo' => 'required|string|max:255',
            'data_nascimento' => 'required|date',
            'morada' => 'required|string|max:255',
            'encarregado1' => 'required|string|max:255',
            'encarregado2' => 'nullable|string|max:255',
            'classe_anterior' => 'required|string|max:50',
            'classe_atual' => 'required|string|max:50',
            'situacao_escolar' => 'required|in:Aprovado,Reprovado,Transferido',
            'contato1' => 'required|string|max:20',
            'contato2' => 'nullable|string|max:20',
            'reparo_especial' => 'nullable|string',
            'atribuir_classe' => 'required|string|max:50',
            'atribuir_turma' => ['required', 'regex:/^TA[0-9]{1,2}$/'],
            'fotografia' => 'nullable|image|max:2048',
            'documentos_historico' => 'nullable|array',
            'documentos_historico.*' => 'mimes:pdf,doc,docx,odt,txt|max:5120',

        ]);

         // Upload da fotografia
        if ($request->hasFile('fotografia')) {
            $validated['fotografia'] = $request->file('fotografia')->store('alunos/fotografias', 'public');
        }

        // Upload de documentos históricos
        if ($request->hasFile('documentos_historico')) {
            $documentosPaths = [];
            foreach ($request->file('documentos_historico') as $documento) {
                $documentosPaths[] = $documento->store('alunos/documentos', 'public');
            }
            $validated['documentos_historico'] = json_encode($documentosPaths);
        }

        $aluno->update($validated);

     return response()->json(['success' => true, 'data' => $aluno], 200);

    }

    /**
     * Remove um aluno
     */
    public function destroy($id)
    {
        $aluno = Aluno::find($id);

        if (!$aluno) {
            return response()->json(['erro' => 'Aluno não encontrado'], 404);
        }

        $aluno->delete();

        return response()->json(['mensagem' => 'Aluno excluído com sucesso'], 200);
    }

    /**
     * Busca alunos por nome
     */
    public function buscarPorNome(Request $request)
    {
        $termo = $request->query('nome') ?? $request->query('q') ?? '';

        $alunos = Aluno::where('nome_completo', 'like', '%' . $termo . '%')->get();

        return response()->json($alunos);
    }

    /**
     * Busca alunos por turma
     */
    public function getPorTurma($codigo)
    {
        try {
            $alunos = Aluno::where('atribuir_turma', $codigo)->get();
            return response()->json($alunos);
        } catch (\Exception $e) {
            return response()->json(['erro' => $e->getMessage()], 500);
        }
    }

    /**
     * Busca genérica por nome ou turma
     */
   public function buscar(Request $request)
{
    $termo = $request->query('termo');

    $alunos = Aluno::where('nome_completo', 'like', '%' . $termo . '%')
        ->select('id', 'nome_completo')
        ->limit(10)
        ->get();

    return response()->json($alunos);
}


    /**
     * Distribuição de alunos por turma
     */
    public function distribuicaoPorTurma()
    {
        $dados = DB::table('alunos')
            ->select('atribuir_turma as turma', DB::raw('count(*) as quantidade'))
            ->groupBy('atribuir_turma')
            ->get();

        return response()->json($dados);
    }

    /**
     * Atribui automaticamente uma turma com base na classe
     */
    public function atribuirTurmaAutomatica($classeId)
    {
        $turmas = Turma::where('classe_id', $classeId)
            ->withCount('alunos')
            ->get()
            ->filter(fn($turma) => $turma->alunos_count <= 35);

        if ($turmas->isEmpty()) {
            return response()->json(['erro' => 'Nenhuma turma disponível'], 404);
        }

        $turmaEscolhida = $turmas->sortBy('alunos_count')->first();

        return response()->json(['turma_id' => $turmaEscolhida->id]);
    }
}