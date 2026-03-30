<?php

namespace App\Http\Controllers;

use App\Http\Resources\ComentarioResource;
use App\Models\Comentario;
use Dedoc\Scramble\Attributes\Endpoint;
use Illuminate\Http\Request;

class ComentarioController extends Controller
{
    #[Endpoint(
        title: 'Listar comentários',
        description: 'Exibe uma lista de comentários, com opção de filtrar por tipo (Post ou Video).'
    )]
    public function index(Request $request)
    {
        $validated = $request->validate([
            'type' => 'nullable|string'
        ]);

        // Obtém todos os comentários
        $comentarios = Comentario::all();

        // Se o tipo for fornecido, filtra os comentários pelo tipo
        if (isset($validated['type'])) {
            $comentarios = $comentarios->filter(function ($comentario) use ($validated) {
                return $comentario->commentable_type === $this->getModelClass($validated['type']);
            });
        }

        return $this->handleSuccessResponse(
            'Listagem de comentários obtidos com sucesso.', 
            ComentarioResource::collection($comentarios)
        );
    }

    #[Endpoint(
        title: 'Criar comentário',
        description: 'Cria um novo comentário em um Post ou Vídeo. O usuário autenticado será definido como autor do comentário.'
    )]
    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'commentable_id' => 'required|integer',
            'commentable_type' => 'required|string|in:Post,Video',
        ]);

        // Verificar se o commentable_id existe na tabela correta
        $table = $validated['commentable_type'] === 'Post' ? 'posts' : 'videos';
        $request->validate([
            'commentable_id' => "exists:$table,id"
        ]);

        $comentario = Comentario::create([
            ...$validated,
            'commentable_type' => $this->getModelClass($validated['commentable_type']),
            'user_id' => auth()->user()->id
        ]);

        if (!$comentario) {
            return $this->handleErrorResponse('Erro ao criar o comentário.', null, 400);
        }

        return $this->handleSuccessResponse(
            'Comentário criado com sucesso.',
            new ComentarioResource($comentario),
            201
        );
    }

    #[Endpoint(
        title: 'Obter comentário',
        description: 'Obtém os detalhes de um comentário específico, incluindo informações do usuário que o criou.'
    )]
    public function show(string $id)
    {
        $comentario = Comentario::with('user')->find($id);
        if(!$comentario) {
            return $this->handleErrorResponse('Comentário não encontrado.', null, 404);
        }

        return $this->handleSuccessResponse(
            'Comentário obtido com sucesso.',
            new ComentarioResource($comentario)
        );
    }

    #[Endpoint(
        title: 'Deletar comentário',
        description: 'Remove um comentário. Apenas o autor do comentário pode deletá-lo.'
    )]
    public function destroy(string $id)
    {
        $comentario = Comentario::find($id);
        if(!$comentario) {
            return $this->handleErrorResponse('Comentário não encontrado.', null, 404);
        }

        $this->authorize('delete', $comentario);

        $comentario->delete();
        return $this->handleSuccessResponse('Comentário deletado com sucesso.', null, 204);
    }

    private function getModelClass(string $type): ?string
    {
        $mappedTypes = [
            'Post' => \App\Models\Post::class,
            'Video' => \App\Models\Video::class
        ];

        return $mappedTypes[$type] ?? null;
    }
}
