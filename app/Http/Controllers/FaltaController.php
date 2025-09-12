<?php

namespace App\Http\Controllers; 
use Illuminate\Http\Request;
use App\Models\Falta;


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
}
