<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ConfiguracaoSistema; // importa o Model
use Illuminate\Support\Facades\Storage;

class ConfiguracaoSistemaController extends Controller
{
    public function index()
    {
        return ConfiguracaoSistema::first();
    }

    public function update(Request $request)
    {
        $config = ConfiguracaoSistema::firstOrCreate([]);
        $config->update($request->all());

        return response()->json([
            'message' => 'Configurações atualizadas com sucesso!',
            'data' => $config
        ]);
    }

    public function uploadLogo(Request $request)
    {
        if ($request->hasFile('logotipo')) {
            $file = $request->file('logotipo');
            $path = $file->store('public/logotipos');

            $config = ConfiguracaoSistema::firstOrCreate([]);
            $config->logotipo = Storage::url($path);
            $config->save();

            return response()->json([
                'message' => 'Logotipo atualizado!',
                'logotipo' => $config->logotipo
            ]);
        }

        return response()->json(['error' => 'Nenhum arquivo enviado.'], 400);
    }
}
