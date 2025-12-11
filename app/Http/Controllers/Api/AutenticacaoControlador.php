<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Servicos\AutenticacaoServico;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AutenticacaoControlador extends Controller
{
    /**
     * @var AutenticacaoServico
     */
    private AutenticacaoServico $autenticacaoServico;

    public function __construct(AutenticacaoServico $autenticacaoServico)
    {
        $this->autenticacaoServico = $autenticacaoServico;
    }

    public function login(Request $requisicao): JsonResponse
    {
        $dados = $requisicao->validate([
            'email' => ['required', 'email'],
            'senha' => ['required', 'string'],
        ]);

        $token = $this->autenticacaoServico
            ->efetuarLogin($dados['email'], $dados['senha']);

        return response()->json([
            'mensagem' => 'Login realizado com sucesso.',
            'token' => $token,
        ]);
    }

    public function logout(Request $requisicao): JsonResponse
    {
        $usuario = $requisicao->user();

        if ($usuario) {
            $this->autenticacaoServico->efetuarLogout($usuario);
        }

        return response()->json([
            'mensagem' => 'Logout realizado com sucesso.',
        ], 200);
    }

    public function usuarioAutenticado(Request $requisicao): JsonResponse
    {
        return response()->json($requisicao->user());
    }
}
