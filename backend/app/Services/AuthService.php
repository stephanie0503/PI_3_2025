<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
 
    public function login(array $credentials)
    {
        // Verificar se as credenciais estão corretas
        if (auth()->attempt($credentials)) {
            $user = auth()->user();
            // Gerar o token de acesso
            return $user->createToken('API Token')->accessToken;
        }

        throw ValidationException::withMessages([
            'email' => ['As credenciais informadas são inválidas.'],
        ]);
    }
}
