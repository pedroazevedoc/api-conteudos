<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
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
            'user_id' => 'required|exists:users,id'
        ]);

        $post = Post::create($validated);
        if (!$post) {
            return $this->handleErrorResponse(
                'Erro ao criar o post.',
                null,
                400
            );
        }

        return $this->handleSuccessResponse(
            'Post criado com sucesso.',
            new PostResource($post),
            201
        );
    }

    public function show(string $id)
    {
        $post = new PostResource(Post::with('user')->find($id));
        if(!$post) {
            return $this->handleErrorResponse(
                'Post não encontrado.',
                null,
                404
            );
        }

        return $this->handleSuccessResponse(
            'Post obtido com sucesso.',
            $post
        );
    }
}