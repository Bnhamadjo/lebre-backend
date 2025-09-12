<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Professor;
use App\Http\Controllers\SalarioController;
use Illuminate\Support\Facades\DB;


class ProfessorController extends Controller
{
    // Lista todos os professores
    public function index()
    {
        return response()->json(Professor::all(), 200);
    }

    // Cria um novo professor
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomeCompleto' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telefone' => 'nullable|string|max:20',
            'nivel' => 'required|string|max:100',
            'numeroDisciplinas' => 'required|integer|min:1|max:10',
            'turmasAfetos' => 'required|array',
            'turmasAfetos.*' => 'string|max:10',
            'periodo' => 'required|string|max:50',
            'permanente' => 'required|boolean',
            'regime' => 'required|string|max:50',
    ]);

    $professor = Professor::create($validated);

    return response()->json($professor, 201);
}

public function comSalarios()
{
    $professores = Professor::with('salarios')->get();

    return response()->json($professores);
}


// Exibe um professor específico
public function show($id)
    {
        $professor = Professor::find($id);

        if (!$professor) {
            return response()->json(['erro' => 'Professor não encontrado'], 404);
        }

        return response()->json($professor, 200);
    }

    // Atualiza um professor
    public function update(Request $request, $id)
    {
        $professor = Professor::find($id);

        if (!$professor) {
            return response()->json(['erro' => 'Professor não encontrado'], 404);
        }

        $validated = $request->validate([
            'nomeCompleto' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telefone' => 'nullable|string|max:20',
            'nivel' => 'required|string|max:100',
            'numeroDisciplinas' => 'required|integer|min:1|max:10',
            'turmasAfetos' => 'required|array',
            'turmasAfetos.*' => 'string|max:10',
            'periodo' => 'required|string|max:50',
            'permanente' => 'required|boolean',
            'regime' => 'required|string|max:50',
        ]);

        $professor->update($validated);

        return response()->json($professor, 200);
    }


public function filtrar(Request $request)
    {
        $query = Professor::query();

        if ($request->filled('periodo')) {
            $query->where('periodo', $request->periodo);
        }

        if ($request->filled('regime')) {
            $query->where('regime', $request->regime);
        }

        if ($request->filled('nivel')) {
            $query->where('nivel', 'like', '%' . $request->nivel . '%');
        }

        if ($request->filled('turma')) {
            $query->where('turmasAfetos', 'like', '%' . $request->turma . '%');
        }

        if ($request->boolean('permanente')) {
            $query->where('permanente', true);
        }

        return response()->json($query->get(), 200);
    }


    // Remove um professor
    public function destroy($id)
    {
        $professor = Professor::find($id);

        if (!$professor) {
            return response()->json(['erro' => 'Professor não encontrado'], 404);
        }

        $professor->delete();

        return response()->json(['mensagem' => 'Professor excluído com sucesso'], 200);
    }
}

