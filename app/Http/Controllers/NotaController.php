<?php

namespace App\Http\Controllers;

use App\Models\Nota;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Exports\NotasExportManual;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use App\Models\Aluno;
use Barryvdh\DomPDF\Facade\Pdf;


class NotaController extends Controller
{
    /**
     * Lista todas as notas com dados do aluno
     */
    public function index()
    {
        $notas = Nota::with('aluno')->get();
        return response()->json($notas, 200);
    }

    /**
     * Cria uma nova nota
     */
   public function store(Request $request)
{
    $validated = $request->validate([
        'aluno_id' => 'required|exists:alunos,id',
        'disciplina' => 'required|string',
        'periodo' => 'required|string',
        'nota' => 'required|numeric|min:0|max:20',
        'ano_letivo' => 'required|integer',
    ]);

    // Verifica se já existe uma nota igual para o mesmo aluno, disciplina, período e ano
    $existe = Nota::where('aluno_id', $validated['aluno_id'])
        ->where('disciplina', $validated['disciplina'])
        ->where('periodo', $validated['periodo'])
        ->where('ano_letivo', $validated['ano_letivo'])
        ->first();

    if ($existe) {
        return response()->json([
            'message' => 'Nota já cadastrada para este aluno, disciplina e período.',
            'nota_existente' => $existe
        ], 409); // 409 Conflict
    }

    $nota = Nota::create($validated);

    return response()->json($nota, 201);
}


    /**
     * Busca notas de um aluno específico
     */
    public function getNotasPorAluno($id)
    {
        $notas = Nota::where('aluno_id', $id)->get();
        return response()->json($notas);
    }

    /**
     * Filtra notas por aluno, disciplina, período e ano letivo
     */
     protected $filtros;

    public function filtrar(Request $request)
    {

        
        $query = Nota::query();

        if ($request->filled('aluno')) {
            $query->whereHas('aluno', function ($q) use ($request) {
                $q->where('nome_completo', 'like', '%' . $request->aluno . '%');
            });
        }

        if ($request->filled('disciplina')) {
            $query->where('disciplina', 'like', '%' . $request->disciplina . '%');
        }

        if ($request->filled('periodo')) {
            $query->where('periodo', $request->periodo);
        }

        if ($request->filled('ano_letivo')) {
            $query->where('ano_letivo', $request->ano_letivo);
        }

        $notas = $query->with('aluno')->get();

        return response()->json($notas, 200);
    }

    /**
     * Exporta notas filtradas para Excel
     */
    public function exportExcelManual(Request $request)
{
    $alunos = Aluno::with(['notas', 'turma'])
        ->whereHas('notas')
        ->when($request->filled('aluno'), fn($q) =>
            $q->where('nome_completo', 'like', '%' . $request->aluno . '%')
        )
        ->get();

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    

    // Cabeçalho
    $sheet->fromArray(['Aluno', 'Turma', 'Disciplina', 'Período', 'Nota', 'Ano Letivo'], null, 'A1');

    $row = 2;

    foreach ($alunos as $aluno) {
        $boletim = [];

        foreach ($aluno->notas as $nota) {
            $disciplina = strtolower(trim($nota->disciplina));
            $periodo = str_replace('º', '', $nota->periodo);

            if (!isset($boletim[$disciplina])) {
                $boletim[$disciplina] = [
                    'periodos' => [],
                    'observacao' => '',
                    'media' => 0
                ];
            }

            $boletim[$disciplina]['periodos'][$periodo] = $nota->nota;

            if ($nota->observacao) {
                $boletim[$disciplina]['observacao'] .= $nota->observacao . ' ';
            }
        }

        foreach ($boletim as $disciplina => $dados) {
            $notasPeriodo = array_values($dados['periodos']);
            $media = count($notasPeriodo) ? array_sum($notasPeriodo) / count($notasPeriodo) : null;
            $dados['media'] = $media ? round($media, 2) : '-';

            $sheet->setCellValue("A{$row}", $nota->aluno->nome_completo ?? 'N/A');
            $sheet->setCellValue("B{$row}", $nota->aluno->atribuir_turma ?? 'N/A');
            $sheet->setCellValue("C{$row}", ucfirst($nota->disciplina));
            $sheet->setCellValue("D{$row}", $dados['periodos']['1'] ?? '-');
            $sheet->setCellValue("E{$row}", $dados['periodos']['2'] ?? '-');
            $sheet->setCellValue("F{$row}", $dados['periodos']['3'] ?? '-');
            $sheet->setCellValue("G{$row}", $dados['media']);
            $sheet->setCellValue("H{$row}", trim($dados['observacao']));
            $row++;
        }
    }

    $writer = new Xlsx($spreadsheet);
    $filename = 'boletim_coletivo.xlsx';
    $temp_file = tempnam(sys_get_temp_dir(), $filename);
    $writer->save($temp_file);

    return response()->download($temp_file, $filename)->deleteFileAfterSend(true);
}

public function exportNotasPDF(Request $request)
{
    $query = Nota::query();
    
    // Apply filters
    if ($request->filled('aluno')) {
        $query->whereHas('aluno', function($q) use ($request) {
            $q->where('nome_completo', 'like', '%' . $request->aluno . '%');
        });
    }
    
    if ($request->filled('disciplina')) {
        $query->where('disciplina', $request->disciplina);
    }
    
    if ($request->filled('periodo')) {
        $query->where('periodo', $request->periodo);
    }
    
    if ($request->filled('ano_letivo')) {
        $query->where('ano_letivo', $request->ano_letivo);
    }
    
    $notas = $query->with('aluno')->get();
    
    // CORREÇÃO: Diretamente usando a view 'notas'
    
    $pdf = Pdf::loadView('pdf.boletim', compact('dados', 'logotipo'));
    $pdf = PDF::loadView('notas', compact('notas'));
    
    return $pdf->download('relatorio_notas_' . date('Y-m-d_H-i-s') . '.pdf');
}

  public function boletimPorAluno($aluno_id)
{
    $aluno = Aluno::with(['turma', 'notas'])->findOrFail($aluno_id);

    if ($aluno->notas->isEmpty()) {
        return response()->json([
            'message' => 'Boletim não criado para este aluno.'
        ], 404);
    }

    // Agrupar notas por disciplina (normalizada) e período
    $boletim = [];

    foreach ($aluno->notas as $nota) {
        $disciplina = strtolower(trim($nota->disciplina)); // normaliza
        $periodo = str_replace('º', '', $nota->periodo);    // "1º" → "1"

        if (!isset($boletim[$disciplina])) {
            $boletim[$disciplina] = [
                'periodos' => [],
                'observacao' => '',
                'media' => 0
            ];
        }

        $boletim[$disciplina]['periodos'][$periodo] = $nota->nota;

        if ($nota->observacao) {
            $boletim[$disciplina]['observacao'] .= $nota->observacao . ' ';
        }
    }

    // Calcular média por disciplina
    foreach ($boletim as $disciplina => $dados) {
        $notasPeriodo = array_values($dados['periodos']);
        $media = count($notasPeriodo) ? array_sum($notasPeriodo) / count($notasPeriodo) : null;
        $boletim[$disciplina]['media'] = $media ? round($media, 2) : '-';
    }

    return response()->json([
        'aluno' => $aluno,
        'boletim' => $boletim
    ]);
}


public function boletimPorTurma($turma_id)
{
    $alunos = Aluno::with(['notas', 'turma'])
        ->where('atribuir_turma', $turma_id)
        ->get();

    if ($alunos->isEmpty()) {
        return response()->json([
            'message' => 'Nenhum aluno encontrado para esta turma.'
        ], 404);
    }

    $boletins = $alunos->map(function ($aluno) {
        $boletim = [];

        foreach ($aluno->notas as $nota) {
            $disciplina = strtolower(trim($nota->disciplina));
            $periodo = str_replace('º', '', $nota->periodo);

            if (!isset($boletim[$disciplina])) {
                $boletim[$disciplina] = [
                    'periodos' => [],
                    'observacao' => '',
                    'media' => 0
                ];
            }

            $boletim[$disciplina]['periodos'][$periodo] = $nota->nota;

            if ($nota->observacao) {
                $boletim[$disciplina]['observacao'] .= $nota->observacao . ' ';
            }
        }

        foreach ($boletim as $disciplina => $dados) {
            $notasPeriodo = array_values($dados['periodos']);
            $media = count($notasPeriodo) ? array_sum($notasPeriodo) / count($notasPeriodo) : null;
            $boletim[$disciplina]['media'] = $media ? round($media, 2) : '-';
        }

        return [
            'aluno' => $aluno,
            'notas' => $boletim
        ];
    });

    return response()->json($boletins);
}

public function alunosComBoletim()
{
    $alunos = Aluno::whereHas('notas')->with('turma')->get();

    return response()->json($alunos);
}



}
