<?php

namespace App\Http\Controllers;

use App\Models\User;
use Dedoc\Scramble\Attributes\Endpoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    #[Endpoint(
        title: 'Registrar usuário',
        description: 'Registra um novo usuário no sistema.'
    )]
    public function register(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user'
        ]);

        $token = $user->createToken('api-token', ['comment-store']);

        return $this->handleSuccessResponse(
            'Usuário registrado com sucesso',
            [
                'user' => $user,
                'token' => $token->plainTextToken
            ],
            201
        );
    }

    #[Endpoint(
        title: 'Login de usuário',
        description: 'Realiza o login de um usuário existente.'
    )]
    public function login(Request $request) {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6'
        ]);

        if (Auth::attempt($validated)) {
            $user = User::where('email', $validated['email'])->first();

            $abilities = $this->getAbilitiesForRole($user->role);

            $token = $user->createToken('api-token', $abilities);

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

    #[Endpoint(
        title: 'Logout de usuário',
        description: 'Realiza o logout de um usuário autenticado.'
    )]
    public function logout(Request $request) {
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

    private function getAbilitiesForRole($role) {
        $mappedAbilities = [
            'admin' => ['post-store', 'video-store', 'comment-store'],
            'user'  => ['comment-store']
        ];

        return $mappedAbilities[$role] ?? [];
    }
}
