<?php

namespace App\DAO;

use App\Models\Pessoa;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PessoaDAO
{
    /**
     * @param  int   $quantidadePorPagina
     * @param  array $filtros
     * @return LengthAwarePaginator
     */
    public function listarPaginado(int $quantidadePorPagina = 15, array $filtros = []): LengthAwarePaginator
    {
        $consulta = Pessoa::query();

        if (!empty($filtros['nome'])) {
            $consulta->where('nome', 'ilike', '%' . $filtros['nome'] . '%');
        }

        if (!empty($filtros['tipo'])) {
            $consulta->where('tipo', $filtros['tipo']);
        }

        if (!empty($filtros['cpf_cnpj'])) {
            $consulta->where('cpf_cnpj', $filtros['cpf_cnpj']);
        }

        if (!empty($filtros['email'])) {
            $consulta->where('email', $filtros['email']);
        }

        return $consulta
            ->orderBy('nome')
            ->paginate($quantidadePorPagina);
    }

    /**
     * @param  int $id
     * @return Pessoa|null
     */
    public function buscarPorId(int $id): ?Pessoa
    {
        return Pessoa::find($id);
    }

    /**
     * @param  array $dados
     * @return Pessoa
     */
    public function criar(array $dados): Pessoa
    {
        return Pessoa::create($dados);
    }

    /**
     * @param  Pessoa $pessoa
     * @param  array  $dados
     * @return Pessoa
     */
    public function atualizar(Pessoa $pessoa, array $dados): Pessoa
    {
        $pessoa->update($dados);

        return $pessoa;
    }

    /**
     * @param  Pessoa $pessoa
     * @return bool
     */
    public function excluir(Pessoa $pessoa): bool
    {
        return (bool) $pessoa->delete();
    }
}
