<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Aluno;

class RelatorioController extends Controller
{
    public function alunos(Request $request)
    {
        $query = DB::table('alunos');

        if ($request->filled('classe_atual')) {
            $query->where('classe_atual', $request->classe_atual);
        }

        if ($request->filled('situacao_escolar')) {
            $query->where('situacao_escolar', $request->situacao_escolar);
        }

        if ($request->filled('atribuir_turma')) {
            $query->where('atribuir_turma', $request->atribuir_turma);
        }

        return response()->json($query->get(), 200);
    }

    public function salarios(Request $request)
{
    $query = DB::table('salarios');

    if ($request->filled('referente_mes')) {
        $query->where('referente_mes', $request->referente_mes);
    }

    if ($request->filled('funcionario_id')) {
        $query->where('funcionario_id', $request->funcionario_id);
    }

    return response()->json($query->get(), 200);
}

public function financeiro(Request $request)
{
    $salarios = DB::table('salarios')
        ->join('professores', 'salarios.funcionario_id', '=', 'professores.id')
        ->select(
            'professores.nomeCompleto as professor',
            'salarios.valor',
            'salarios.referente_mes',
            'salarios.data_pagamento',
            'salarios.recibo_id'
        );

    if ($request->filled('referente_mes')) {
        $salarios->where('salarios.referente_mes', $request->referente_mes);
    }

    if ($request->filled('funcionario_id')) {
        $salarios->where('salarios.funcionario_id', $request->funcionario_id);
    }

    return response()->json($salarios->get(), 200);
}


}
