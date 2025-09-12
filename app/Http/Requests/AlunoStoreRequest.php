<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AlunoStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [

            'nome_completo' => 'required|string|max:255',
            'data_nascimento' => 'required|date',
            'morada' => 'required|string|max:255',
            'encarregado1' => 'required|string|max:255',
            'encarregado2' => 'nullable|string|max:255',
            'classe_anterior' => 'required|string|max:50',
            'classe_atual' => 'required|string|max:50',
            'situacao_escolar' => 'required|in:Aprovado,Reprovado,Transferido',
            'contato1' => 'required|string|max:20',
            'contato2' => 'nullable|string|max:20',
            'reparo_especial' => 'nullable|string',
            'atribuir_classe' => 'required|string|max:50',
            'atribuir_turma' => ['required', 'regex:/^TA[0-9]{1}$/'],
            'fotografia' => 'nullable|image|max:2048',
            'documentos_historico.*' => 'nullable|file|max:5120',
            //
        ];
    }
}
