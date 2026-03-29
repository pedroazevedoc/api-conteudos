<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', User::class);
        
        return $this->handleSuccessResponse(
            'Listagem de usuários obtidas com sucesso.', 
            UserResource::collection(User::all())
        );
    }

    public function show(string $id)
    {
        $user = User::find($id);
        if(!$user) {
            return $this->handleErrorResponse('Usuário não encontrado.', null, 404);
        }

        $this->authorize('view', $user);

        return $this->handleSuccessResponse(
            'Usuário obtido com sucesso.', 
            new UserResource($user),
        );
    }
}
