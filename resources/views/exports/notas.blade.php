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
            <td>{{ $nota->aluno->nome_completo }}</td>
            <td>{{ $nota->disciplina }}</td>
            <td>{{ $nota->periodo }}</td>
            <td>{{ $nota->nota }}</td>
            <td>{{ $nota->ano_letivo }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
