<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return $this->handleSuccessResponse(
            'Listagem de usuários obtidas com sucesso.', 
            UserResource::collection(User::all())
        );
    }

    public function show(string $id)
    {
        $user = new UserResource(User::find($id));
        if(!$user) {
            return $this->handleErrorResponse(
                'Usuário não encontrado.', 
                null, 
                404
            );
        }

        return $this->handleSuccessResponse(
            'Usuário obtido com sucesso.', 
            $user
        );
    }
}
