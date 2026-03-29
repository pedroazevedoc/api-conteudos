<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    function register(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password'])
        ]);

        $token = $user->createToken('api-token', ['post:read', 'post:create']);

        return $this->handleSuccessResponse(
            'Usuário registrado com sucesso',
            [
                'user' => $user,
                'token' => $token->plainTextToken
            ],
            201
        );
    }

    function login(Request $request) {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6'
        ]);

        if (Auth::attempt($validated)) {
            $user = User::where('email', $validated['email'])->first();

            $token = $user->createToken('api-token', ['post:read', 'post:create']);

            return $this->handleSuccessResponse(
                'Usuário logado com sucesso',
                [
                    'user' => $user,
                    'token' => $token->plainTextToken
                ]
            );
        }

        return $this->handleErrorResponse(
            'Credenciais inválidas',
            null,
            401
        );
    }

    function logout(Request $request) {
        $token = $request->bearerToken();
        if (!$token) {
            return $this->handleErrorResponse(
                'Token não fornecido',
                null,
                400
            );
        }

        $access_token = PersonalAccessToken::findToken($token);
        if(!$access_token) {
            return $this->handleErrorResponse(
                'Token inválido',
                null,
                400
            );
        }

        $access_token->delete();
        return $this->handleSuccessResponse(
            'Usuário deslogado com sucesso',
            null
        );
    }
}
