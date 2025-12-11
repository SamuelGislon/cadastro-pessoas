<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pessoa\PessoaCriarRequisicao;
use App\Http\Requests\Pessoa\PessoaAtualizarRequisicao;
use App\Servicos\PessoaServico;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Pessoa;

class PessoaControlador extends Controller
{
    /**
     * @var PessoaServico
     */
    private PessoaServico $pessoaServico;

    public function __construct(PessoaServico $pessoaServico)
    {
        $this->pessoaServico = $pessoaServico;
    }

    public function index(Request $requisicao): JsonResponse
    {
        $filtros = $requisicao->only(['nome', 'tipo', 'cpf_cnpj', 'email']);
        $quantidadePorPagina = (int) $requisicao->get('por_pagina', 15);

        $paginacao = $this->pessoaServico->listarPessoas($filtros, $quantidadePorPagina);

        return response()->json($paginacao);
    }

    public function store(PessoaCriarRequisicao $requisicao): JsonResponse
    {
        $pessoa = $this->pessoaServico->criarPessoa($requisicao->validated());

        return response()->json($pessoa, 201);
    }

    public function show(int $id): JsonResponse
    {
        $pessoa = $this->pessoaServico->obterPessoa($id);

        return response()->json($pessoa);
    }

    public function update(PessoaAtualizarRequisicao $requisicao, Pessoa $pessoa): JsonResponse
    {
        $pessoaAtualizada = $this->pessoaServico->atualizarPessoa(
            $pessoa->id,
            $requisicao->validated()
        );

        return response()->json($pessoaAtualizada);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->pessoaServico->excluirPessoa($id);

        return response()->json(null, 204);
    }
}
