<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Relatório de Notas - {{ date('d/m/Y H:i') }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        h1 { color: #333; text-align: center; margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .no-data { color: #666; font-style: italic; text-align: center; margin-top: 40px; }
        .header-info { margin-bottom: 20px; }
        .footer { margin-top: 40px; text-align: center; color: #666; font-size: 12px; }
    </style>
</head>
<body>
    <h1>Relatório de Notas</h1>
    
    <div class="header-info">
        <p><strong>Data de emissão:</strong> {{ date('d/m/Y H:i') }}</p>
        <p><strong>Total de registros:</strong> {{ $notas->count() }}</p>
    </div>

    @if($notas->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Aluno</th>
                    <th>Disciplina</th>
                    <th>Período</th>
                    <th>Nota</th>
                    <th>Ano Letivo</th>
                </tr>
            </thead>
            <tbody>
                @foreach($notas as $nota)
                <tr>
                    <td>{{ $nota->aluno->nome_completo ?? 'N/A' }}</td>
                    <td>{{ $nota->disciplina ?? 'N/A' }}</td>
                    <td>{{ $nota->periodo ?? 'N/A' }}</td>
                    <td>{{ $nota->nota ?? 'N/A' }}</td>
                    <td>{{ $nota->ano_letivo ?? 'N/A' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="no-data">Nenhuma nota encontrada para os critérios selecionados.</p>
    @endif

    <div class="footer">
        Gerado automaticamente em {{ date('d/m/Y H:i:s') }}
    </div>
</body>
</html>