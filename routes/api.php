<?php
use App\Exports\NotasExport;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController,
    AlunoController,
    ProfessorController,
    DespesaController,
    FinanceiroController,
    PropinaController,
    SalarioController,
    FundoController,
    RelatorioController,
    ReceitaController,
    FaltaController,
    NotaController,
    DisciplinaController,
    ConfiguracaoSistemaController,
    
    


};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Algumas rotas públicas, outras protegidas por autenticação via Sanctum.
*/

// 🔓 Autenticação
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/disciplinas', [DisciplinaController::class, 'index']);
Route::post('/disciplinas', [DisciplinaController::class, 'store']);
Route::get('/notas/export/manual', [NotaController::class, 'exportExcelManual']);


// 🔓 Alunos
Route::prefix('alunos')->group(function () {
    Route::post('/', [AlunoController::class, 'store']);
    Route::get('/', [AlunoController::class, 'index']);
    Route::get('/distribuicao-por-turma', [AlunoController::class, 'distribuicaoPorTurma']);
    Route::get('/buscar', [AlunoController::class, 'buscarPorNome']);
    Route::get('/busca', [AlunoController::class, 'buscar']); // ✅ esta é a rota usada pelo autocomplete
    Route::get('/turma/{codigo}', [AlunoController::class, 'getPorTurma']);
    Route::put('/{id}', [AlunoController::class, 'update']);
    Route::get('/{id}', [AlunoController::class, 'show']);
    Route::delete('/{id}', [AlunoController::class, 'destroy']);
    Route::apiResource('faltas', FaltaController::class);
    Route::post('/faltas', [FaltaController::class, 'store']);
    Route::get('/faltas', [FaltaController::class, 'index']);
    Route::get('/boletim/aluno/{id}', [NotaController::class, 'boletimPorAluno']);
    Route::get('/boletim/turma/{turma_id}', [NotaController::class, 'boletimPorTurma']);

});

// 🔓 Notas 

Route::apiResource('notas', NotaController::class);
Route::get('/alunos/busca', [AlunoController::class, 'buscar']);
Route::post('/notas/filtrar', [NotaController::class, 'filtrar']);
Route::get('/alunos/{id}/notas', [NotaController::class, 'getNotasPorAluno']);
Route::get('/alunos/notas/export/manual', [NotaController::class, 'exportExcelManual']);
Route::get('/boletim/aluno/{aluno_id}', [NotaController::class, 'boletimPorAluno']);
Route::get('/boletim/turma/{turma_id}', [NotaController::class, 'boletimPorTurma']);
Route::get('/boletim/aluno/{aluno_id}', [NotaController::class, 'boletimPorAluno']);

Route::get('/boletim/alunos/com-notas', [NotaController::class, 'alunosComBoletim']);

Route::get('/notas/export/pdf', [NotaController::class, 'exportNotasPDF']);


Route::get('/professores/com-salarios', [ProfessorController::class, 'comSalarios']);
Route::get('/financas/resumo-mensal', [FinanceiroController::class, 'resumoMensal']);
Route::post('/professores/filtrar', [ProfessorController::class, 'filtrar']);


Route::get('/propinas', [PropinaController::class, 'listarPorMes']);
Route::get('/receitas', [ReceitaController::class, 'listarPorMes']);
Route::get('/salarios', [SalarioController::class, 'listarPorMesEProfessor']);


Route::get('/despesas', [DespesaController::class, 'listarPorMes']);

Route::post('/disciplinas', [DisciplinaController::class, 'store']);
Route::get('/disciplinas', [DisciplinaController::class, 'index']);
// Ver disciplinas de um professor
Route::get('/professores/{id}/disciplinas', [ProfessorController::class, 'disciplinas']);
// Ver boletim de um aluno
Route::get('/alunos/{id}/boletim', [AlunoController::class, 'boletim']);
// Relatórios
Route::post('/relatorios/receitas', [RelatorioController::class, 'receitas']);
Route::post('/relatorios/despesas', [RelatorioController::class, 'despesas']);

Route::post('/relatorios/alunos', [RelatorioController::class, 'alunos']);
Route::post('/relatorios/salarios', [RelatorioController::class, 'salarios']);

// 🔓 Professores
Route::prefix('professores')->group(function () {
    Route::post('/', [ProfessorController::class, 'store']);
    Route::get('/', [ProfessorController::class, 'index']);
    Route::get('/{id}', [ProfessorController::class, 'show']);
    Route::get('/com-salarios', [ProfessorController::class, 'comSalarios']);
    Route::put('/{id}', [ProfessorController::class, 'update']);
    Route::get('/salarios/historico', [SalarioController::class, 'historico']);
    Route::get('/buscar', [ProfessorController::class, 'buscarPorNome']);

    });

    



// 🔓 Despesas
Route::prefix('despesas')->group(function () {
    Route::get('/', [DespesaController::class, 'index']);
        Route::post('/', [DespesaController::class, 'store']);

    // Se quiser adicionar outras ações, como store ou update, adicione aqui
});

// 🔓 Fundo
Route::prefix('fundo')->group(function () {
    Route::get('/', [FundoController::class, 'index']);
    Route::post('/', [FundoController::class, 'store']);

});

// 🔓 Salários
Route::prefix('salarios')->group(function () {
    Route::get('/', [SalarioController::class, 'index']);
    Route::post('/', [SalarioController::class, 'store']);
    Route::get('/historico', [SalarioController::class, 'historico']);

});

// 🔓 Financeiro
Route::prefix('financeiro')->group(function () {
    Route::post('/propinas/pagar', [PropinaController::class, 'pagar']);
    Route::post('/salarios/pagar', [FinanceiroController::class, 'pagarSalario']);
    Route::post('/despesas/registrar', [FinanceiroController::class, 'registrarDespesa']);
    Route::post('/fundo/adicionar', [FinanceiroController::class, 'adicionarFundo']);
    Route::get('/resumo', [FinanceiroController::class, 'resumoMensal']);

});

Route::get('/configuracoes', [ConfiguracaoSistemaController::class, 'index']);
Route::post('/configuracoes', [ConfiguracaoSistemaController::class, 'update']);
Route::post('/configuracoes/logotipo', [ConfiguracaoSistemaController::class, 'uploadLogo']);


// 🔐 Protegidas por autenticação Sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn(Request $request) => $request->user());
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
});