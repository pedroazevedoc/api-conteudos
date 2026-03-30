<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Dedoc\Scramble\Attributes\Endpoint;

class UserController extends Controller
{
    #[Endpoint(
        title: 'Listar usuários',
        description: 'Exibe uma lista de usuários, incluindo informações do autor. Apenas usuários com a role "admin" podem acessar esta rota.'
    )]
    public function index()
    {
        $this->authorize('viewAny', User::class);
        
        return $this->handleSuccessResponse(
            'Listagem de usuários obtidas com sucesso.', 
            UserResource::collection(User::all())
        );
    }

    #[Endpoint(
        title: 'Obter usuário',
        description: 'Obtém os detalhes de um usuário específico, incluindo informações do autor. Apenas usuários com a role "admin" ou o próprio usuário podem acessar esta rota.'
    )]
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
