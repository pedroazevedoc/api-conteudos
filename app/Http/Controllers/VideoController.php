<?php

namespace App\Http\Controllers;

use App\Http\Resources\VideoResource;
use App\Http\Traits\HasComments;
use App\Models\Video;
use Dedoc\Scramble\Attributes\Endpoint;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    use HasComments;
    
    #[Endpoint(
        title: 'Listar vídeos',
        description: 'Exibe uma lista de vídeos, incluindo informações do autor.'
    )]
    public function index()
    {
        return $this->handleSuccessResponse(
            'Listagem de vídeos obtidos com sucesso.', 
            VideoResource::collection(Video::with('user')->get())
        );
    }

    #[Endpoint(
        title: 'Criar vídeo',
        description: 'Cria um novo vídeo com o título, URL e descrição especificados. Apenas usuários com a role "admin" podem criar vídeos.'
    )]
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'url' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $video = Video::create([
            ...$validated,
            'user_id' => auth()->user()->id
        ]);
        
        if (!$video) {
            return $this->handleErrorResponse('Erro ao criar o vídeo.', null, 400);
        }

        return $this->handleSuccessResponse(
            'Vídeo criado com sucesso.',
            new VideoResource($video),
            201
        );
    }

    #[Endpoint(
        title: 'Obter vídeo',
        description: 'Obtém os detalhes de um vídeo específico, incluindo informações do autor.'
    )]
    public function show(string $id)
    {
        $video = Video::with('user')->find($id);
        if(!$video) {
            return $this->handleErrorResponse('Vídeo não encontrado.', null, 404);
        }

        return $this->handleSuccessResponse(
            'Vídeo obtido com sucesso.',
            new VideoResource($video)
        );
    }

    #[Endpoint(
        title: 'Listar comentários de um vídeo',
        description: 'Exibe uma lista de comentários associados a um vídeo específico.'
    )]
    public function comentarios(string $id)
    {
        $video = Video::find($id);
        if(!$video) {
            return $this->handleErrorResponse('Vídeo não encontrado.', null, 404);
        }

        return $this->getCommentsForModel($video);
    }
}
