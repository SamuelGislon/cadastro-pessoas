<?php

namespace App\Http\Requests\Pessoa;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PessoaCriarRequisicao extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome' => ['required', 'string', 'max:255'],
            'tipo' => [
                'required',
                Rule::in(['fisica', 'juridica']),
            ],
            'cpf_cnpj' => [
                'required',
                'string',
                'max:18',
                'unique:pessoas,cpf_cnpj',
            ],
            'telefone' => ['nullable', 'string', 'max:20'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:pessoas,email',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'O nome é obrigatório.',
            'tipo.required' => 'O tipo é obrigatório.',
            'tipo.in' => 'O tipo deve ser "fisica" ou "juridica".',
            'cpf_cnpj.required' => 'O CPF/CNPJ é obrigatório.',
            'cpf_cnpj.unique' => 'Já existe uma pessoa cadastrada com este CPF/CNPJ.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'Informe um e-mail válido.',
            'email.unique' => 'Já existe uma pessoa cadastrada com este e-mail.',
        ];
    }

    public function withValidator($validador): void
    {
        $validador->after(function ($validador) {
            $tipo = $this->input('tipo');
            $documento = $this->input('cpf_cnpj');

            if (!$tipo || !$documento) {
                return;
            }

            $numeros = preg_replace('/\D/', '', $documento);

            if ($tipo === 'fisica' && !$this->cpfValido($numeros)) {
                $validador->errors()->add('cpf_cnpj', 'CPF inválido.');
            }

            if ($tipo === 'juridica' && !$this->cnpjValido($numeros)) {
                $validador->errors()->add('cpf_cnpj', 'CNPJ inválido.');
            }
        });
    }

    private function cpfValido(?string $cpf): bool
    {
        if (!$cpf || strlen($cpf) !== 11) {
            return false;
        }

        if (preg_match('/^(\\d)\\1{10}$/', $cpf)) {
            return false;
        }

        $soma = 0;
        for ($i = 0, $peso = 10; $i < 9; $i++, $peso--) {
            $soma += (int) $cpf[$i] * $peso;
        }

        $resto = $soma % 11;
        $digito1 = $resto < 2 ? 0 : 11 - $resto;

        if ($digito1 !== (int) $cpf[9]) {
            return false;
        }

        $soma = 0;
        for ($i = 0, $peso = 11; $i < 10; $i++, $peso--) {
            $soma += (int) $cpf[$i] * $peso;
        }

        $resto = $soma % 11;
        $digito2 = $resto < 2 ? 0 : 11 - $resto;

        return $digito2 === (int) $cpf[10];
    }

    private function cnpjValido(?string $cnpj): bool
    {
        if (!$cnpj || strlen($cnpj) !== 14) {
            return false;
        }

        if (preg_match('/^(\\d)\\1{13}$/', $cnpj)) {
            return false;
        }

        $pesosPrimeiro = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        $pesosSegundo =  [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

        $soma = 0;
        for ($i = 0; $i < 12; $i++) {
            $soma += (int) $cnpj[$i] * $pesosPrimeiro[$i];
        }

        $resto = $soma % 11;
        $digito1 = $resto < 2 ? 0 : 11 - $resto;

        if ($digito1 !== (int) $cnpj[12]) {
            return false;
        }

        $soma = 0;
        for ($i = 0; $i < 13; $i++) {
            $soma += (int) $cnpj[$i] * $pesosSegundo[$i];
        }

        $resto = $soma % 11;
        $digito2 = $resto < 2 ? 0 : 11 - $resto;

        return $digito2 === (int) $cnpj[13];
    }
}
