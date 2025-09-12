<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Salario;
use App\Models\Professor;

class SalarioController extends Controller
{

    public function index()
 {
    $salarios = Salario::with('professor')->orderBy('data_pagamento', 'desc')->get();

    return response()->json($salarios);
 }

    public function store(Request $request)
    {
        $request->validate([
            'professor_id' => 'required|exists:professores,id',
            'valor' => 'required|numeric|min:0',
            'referente_mes' => 'required|string',
            'data_pagamento' => 'required|date',
        ]);

        $reciboId = 'SAL-' . uniqid();

        $salario = Salario::create([
            
            'funcionario_id' => $request->professor_id,
            'valor' => $request->valor,
            'referente_mes' => $request->referente_mes,
            'data_pagamento' => $request->data_pagamento,
            'recibo_id' => $reciboId
        ]);

       $professor = Professor::find($request->professor_id);

 return response()->json([
    'reciboSalario' => [
        'professor' => [
            'nomeCompleto' => $professor->nomeCompleto,
            'nivel' => $professor->nivel,
            'turmasAfetos' => $professor->turmasAfetos,
        ],
        'valor' => $salario->valor,
        'referente_mes' => $salario->referente_mes,
        'data_pagamento' => $salario->data_pagamento,
        'recibo_id' => $salario->recibo_id
    ]
 ]);


    }

    public function historico(Request $request)
 {
    $query = Salario::with('professor')->orderBy('data_pagamento', 'desc');

    if ($request->has('professor_id')) {
        $query->where('funcionario_id', $request->professor_id);
    }

    if ($request->has('mes')) {
        $query->where('referente_mes', 'like', '%' . $request->mes . '%');
    }

    return response()->json($query->get());
 }

  public function listarPorMesEProfessor(Request $request)
 {
    $mes = $request->query('mes');
    $professor = $request->query('professor');

    $query = Salario::whereMonth('data_pagamento', $mes);

    if ($professor) {
        $query->where('professor_nome', 'like', "%$professor%");
    }

    return response()->json($query->get());
 }


}
