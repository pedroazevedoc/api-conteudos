<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Http\Traits\HasComments;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    use HasComments;

    public function index()
    {
        return $this->handleSuccessResponse(
            'Listagem de posts obtidos com sucesso.', 
            PostResource::collection(Post::with('user')->get())
        );
    }

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

    public function comentarios(string $id)
    {
        $post = Post::find($id);
        if(!$post) {
            return $this->handleErrorResponse('Post não encontrado.', null, 404);
        }

        return $this->getCommentsForModel($post);
    }
}