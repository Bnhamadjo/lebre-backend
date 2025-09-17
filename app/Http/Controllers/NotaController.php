<?php

namespace App\Http\Controllers;

use App\Models\Nota;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Exports\NotasExportManual;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

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
        $notas = Nota::with('aluno')
            ->when($request->filled('aluno'), fn($q) =>
                $q->whereHas('aluno', fn($q2) =>
                    $q2->where('nome_completo', 'like', '%' . $request->aluno . '%')
                )
            )
            ->when($request->filled('disciplina'), fn($q) =>
                $q->where('disciplina', 'like', '%' . $request->disciplina . '%')
            )
            ->when($request->filled('periodo'), fn($q) =>
                $q->where('periodo', $request->periodo)
            )
            ->when($request->filled('ano_letivo'), fn($q) =>
                $q->where('ano_letivo', $request->ano_letivo)
            )
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray(['Aluno', 'Disciplina', 'Período', 'Nota', 'Ano Letivo'], NULL, 'A1');

        $row = 2;

        
        // Usar a classe exportadora
        {
     $exporter = new NotasExportManual($request);
     return $exporter->generateExcel();
        }

        foreach ($notas as $nota) {
            $sheet->setCellValue("A{$row}", $nota->aluno->nome_completo ?? 'N/A');
            $sheet->setCellValue("B{$row}", $nota->disciplina);
            $sheet->setCellValue("C{$row}", $nota->periodo);
            $sheet->setCellValue("D{$row}", $nota->nota);
            $sheet->setCellValue("E{$row}", $nota->ano_letivo);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'notas.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $filename);
        $writer->save($temp_file);

        return response()->download($temp_file, $filename)->deleteFileAfterSend(true);
    }
}
