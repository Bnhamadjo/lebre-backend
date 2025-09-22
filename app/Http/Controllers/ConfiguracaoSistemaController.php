<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ConfiguracaoSistema;
use Illuminate\Support\Facades\Storage;

class ConfiguracaoSistemaController extends Controller
{
    public function index()
    {
        return ConfiguracaoSistema::first();
    }

    public function store(Request $request)
    {
        $config = ConfiguracaoSistema::firstOrCreate([]);

        if ($request->hasFile('logotipo')) {
            $file = $request->file('logotipo');
            $path = $file->store('logotipos', 'public');
            $config->logotipo = Storage::url($path);
        }

        $config->nome_escola = $request->input('nome_escola');
        $config->cor_sidebar = $request->input('cor_sidebar');
        $config->cor_fundo = $request->input('cor_fundo');
        $config->cor_botao = $request->input('cor_botao');
        $config->tema = $request->input('tema');
        $config->idioma = $request->input('idioma');
        $config->formato_data = $request->input('formato_data');

        $config->save();

        return response()->json([
            'message' => 'Configurações salvas com sucesso!',
            'data' => $config
        ]);
    }

    public function update(Request $request)
    {
        $config = ConfiguracaoSistema::firstOrCreate([]);

        if ($request->hasFile('logotipo')) {
            $file = $request->file('logotipo');
            $path = $file->store('logotipos', 'public');
            $config->logotipo = Storage::url($path);
        }

        $config->nome_escola = $request->input('nome_escola');
        $config->cor_sidebar = $request->input('cor_sidebar');
        $config->cor_fundo = $request->input('cor_fundo');
        $config->cor_botao = $request->input('cor_botao');
        $config->tema = $request->input('tema');
        $config->idioma = $request->input('idioma');
        $config->formato_data = $request->input('formato_data');

        $config->save();

        return response()->json([
            'message' => 'Configurações atualizadas com sucesso!',
            'data' => $config
        ]);
    }
}
