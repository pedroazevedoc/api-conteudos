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

        return response()->json([
            'status' => 'success',
            'response' => 'Usuário registrado com sucesso',
            'payload' => [
                'user' => $user,
                'token' => $token->plainTextToken
            ]
        ], 201);
    }

    function login(Request $request) {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6'
        ]);

        if (Auth::attempt($validated)) {
            $user = User::where('email', $validated['email'])->first();

            $token = $user->createToken('api-token', ['post:read', 'post:create']);

            return response()->json([
                'status' => 'success',
                'response' => 'Usuário logado com sucesso',
                'payload' => [
                    'user' => $user,
                    'token' => $token->plainTextToken
                ]
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'response' => 'Credenciais inválidas'
        ], 401);
    }

    function logout(Request $request) {
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'response' => 'Token não fornecido'
            ], 400);
        }

        $access_token = PersonalAccessToken::findToken($token);
        if(!$access_token) {
            return response()->json([
                'status' => 'error',
                'response' => 'Token inválido'
            ], 400);
        }

        $access_token->delete();
        return response()->json([
            'status' => 'success',
            'response' => 'Usuário deslogado com sucesso'
        ], 200);
    }
}
