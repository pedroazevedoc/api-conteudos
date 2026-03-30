<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Http\Traits\HasComments;
use App\Models\Post;
use Dedoc\Scramble\Attributes\Endpoint;
use Illuminate\Http\Request;

class PostController extends Controller
{
    use HasComments;

    #[Endpoint(
        title: 'Listar posts',
        description: 'Exibe uma lista de posts, incluindo informações do autor.'
    )]
    public function index()
    {
        return $this->handleSuccessResponse(
            'Listagem de posts obtidos com sucesso.', 
            PostResource::collection(Post::with('user')->get())
        );
    }

    #[Endpoint(
        title: 'Criar post',
        description: 'Cria um novo post com o título e conteúdo especificados. Apenas usuários com a role "admin" podem criar posts.'
    )]
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
        ]);

        $post = Post::create([
            ...$validated,
            'user_id' => auth()->user()->id
        ]);

        if (!$post) {
            return $this->handleErrorResponse('Erro ao criar o post.', null, 400);
        }

        return $this->handleSuccessResponse(
            'Post criado com sucesso.',
            new PostResource($post),
            201
        );
    }

    #[Endpoint(
        title: 'Obter post',
        description: 'Obtém os detalhes de um post específico, incluindo informações do autor.'
    )]
    public function show(string $id)
    {
        $post = Post::with('user')->find($id);
        if(!$post) {
            return $this->handleErrorResponse('Post não encontrado.', null, 404);
        }

        return $this->handleSuccessResponse(
            'Post obtido com sucesso.',
            new PostResource($post)
        );
    }

    #[Endpoint(
        title: 'Listar comentários de um post',
        description: 'Exibe uma lista de comentários associados a um post específico.'
    )]
    public function comentarios(string $id)
    {
        $post = Post::find($id);
        if(!$post) {
            return $this->handleErrorResponse('Post não encontrado.', null, 404);
        }

        return $this->getCommentsForModel($post);
    }
}