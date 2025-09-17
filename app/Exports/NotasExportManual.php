<?php

namespace App\Exports;

use App\Models\Nota;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class NotasExportManual
{
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function generateExcel(): BinaryFileResponse
    {
        $notas = Nota::with('aluno')
            ->when($this->request->filled('aluno'), function ($q) {
                $q->whereHas('aluno', function ($q2) {
                    $q2->where('nome_completo', 'like', '%' . $this->request->input('aluno') . '%');
                });
            })
            ->when($this->request->filled('disciplina'), function ($q) {
                $q->where('disciplina', 'like', '%' . $this->request->input('disciplina') . '%');
            })
            ->when($this->request->filled('periodo'), function ($q) {
                $q->where('periodo', $this->request->input('periodo'));
            })
            ->when($this->request->filled('ano_letivo'), function ($q) {
                $q->where('ano_letivo', $this->request->input('ano_letivo'));
            })
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Cabeçalhos
        $sheet->fromArray(['Aluno', 'Disciplina', 'Período', 'Nota', 'Ano Letivo'], null, 'A1');

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
        $filename = 'notas_' . now()->format('Ymd_His') . '.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $filename);
        $writer->save($temp_file);

        return response()->download($temp_file, $filename)->deleteFileAfterSend(true);
    }
}
