<?php

namespace App\Servicos;

use App\DAO\PessoaDAO;
use App\Models\Pessoa;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use InvalidArgumentException;

class PessoaServico
{
    /**
     * @var PessoaDAO
     */
    private PessoaDAO $pessoaDAO;

    public function __construct(PessoaDAO $pessoaDAO)
    {
        $this->pessoaDAO = $pessoaDAO;
    }

    /**
     * @param  array $filtros
     * @param  int   $quantidadePorPagina
     * @return LengthAwarePaginator
     */
    public function listarPessoas(array $filtros = [], int $quantidadePorPagina = 15): LengthAwarePaginator
    {
        return $this->pessoaDAO->listarPaginado($quantidadePorPagina, $filtros);
    }

    /**
     * @param  int $id
     * @return Pessoa
     */
    public function obterPessoa(int $id): Pessoa
    {
        $pessoa = $this->pessoaDAO->buscarPorId($id);

        if (!$pessoa) {
            throw new ModelNotFoundException('Pessoa não encontrada.');
        }
        
        return $pessoa;
    }

    /**
     * @param  array $dados
     * @return Pessoa
     */
    public function criarPessoa(array $dados): Pessoa
    {
        $this->garantirTipoValido($dados);

        return $this->pessoaDAO->criar($dados);
    }

    /**
     * @param  int   $id
     * @param  array $dados
     * @return Pessoa
     */
    public function atualizarPessoa(int $id, array $dados): Pessoa
    {
        $pessoa = $this->obterPessoa($id);

        $this->garantirTipoValido($dados);

        return $this->pessoaDAO->atualizar($pessoa, $dados);
    }

    /**
     * @param  int $id
     * @return void
     */
    public function excluirPessoa(int $id): void
    {
        $pessoa = $this->obterPessoa($id);

        $this->pessoaDAO->excluir($pessoa);
    }

    /**
     * @param  array $dados
     * @return void
     */
    private function garantirTipoValido(array $dados): void
    {
        if (!isset($dados['tipo'])) {
            return;
        }

        $tiposValidos = [
            Pessoa::TIPO_FISICA,
            Pessoa::TIPO_JURIDICA,
        ];

        if (!in_array($dados['tipo'], $tiposValidos, true)) {
            throw new InvalidArgumentException(
                'Tipo de pessoa inválido. Use "fisica" ou "juridica".'
            );
        }
    }
}
