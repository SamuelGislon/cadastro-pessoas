<?php

namespace App\Servicos;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AutenticacaoServico
{
    public function efetuarLogin(string $email, string $senha): string
    {
        /** @var User|null $usuario */
        $usuario = User::where('email', $email)->first();

        if (!$usuario || !Hash::check($senha, $usuario->password)) {
            throw ValidationException::withMessages([
                'email' => ['Credenciais inválidas.'],
            ]);
        }

        $usuario->tokens()->delete();

        $token = $usuario->createToken('token_api_pessoas');

        return $token->plainTextToken;
    }

    public function efetuarLogout(User $usuario): void
    {
        $token = $usuario->currentAccessToken();

        if ($token) {
            $usuario->tokens()->where('id', $token->id)->delete();
        }
    }
}
