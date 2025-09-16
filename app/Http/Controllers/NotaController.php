<?php

namespace App\Http\Controllers;

use App\Models\Nota;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class NotaController extends Controller
{

     public function index()
    {
        // Example response
        return response()->json([
            ['id' => 1, 'nota' => 18],
            ['id' => 2, 'nota' => 15]
        ]);
    }
    // Exportar notas para Excel com filtros manuais

    public function exportExcelManual(Request $request)
    {
        // Buscar notas com filtros
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

        // Criar planilha
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Cabeçalhos
        $sheet->fromArray(['Aluno', 'Disciplina', 'Período', 'Nota', 'Ano Letivo'], NULL, 'A1');

        // Dados
        $row = 2;
        foreach ($notas as $nota) {
            $sheet->setCellValue("A{$row}", $nota->aluno->nome_completo ?? 'N/A');
            $sheet->setCellValue("B{$row}", $nota->disciplina);
            $sheet->setCellValue("C{$row}", $nota->periodo);
            $sheet->setCellValue("D{$row}", $nota->nota);
            $sheet->setCellValue("E{$row}", $nota->ano_letivo);
            $row++;
        }

        // Gerar arquivo temporário
        $writer = new Xlsx($spreadsheet);
        $filename = 'notas.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $filename);
        $writer->save($temp_file);

        // Retornar para download
        return response()->download($temp_file, $filename)->deleteFileAfterSend(true);
    }
}
