<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function register(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users,email',
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
                'user' => new UserResource($user),
                'token' => $token->plainTextToken
            ],
            201
        );
    }

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
                    'user' => new UserResource($user),
                    'token' => $token->plainTextToken
                ]
            );
        }

        return $this->handleErrorResponse('Credenciais inválidas', null, 401);
    }

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
            return $this->handleErrorResponse('Token inválido', null, 400);
        }

        $access_token->delete();
        return $this->handleSuccessResponse(
            'Usuário deslogado com sucesso',
            null, 
            204
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
