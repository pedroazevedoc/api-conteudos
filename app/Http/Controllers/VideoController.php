<?php

namespace App\Http\Controllers;

use App\Http\Resources\VideoResource;
use App\Http\Traits\HasComments;
use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    use HasComments;
    
    public function index()
    {
        return $this->handleSuccessResponse(
            'Listagem de vídeos obtidos com sucesso.', 
            VideoResource::collection(Video::with('user')->get())
        );
    }

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
            return $this->handleErrorResponse(
                'Erro ao criar o vídeo.',
                null,
                400
            );
        }

        return $this->handleSuccessResponse(
            'Vídeo criado com sucesso.',
            new VideoResource($video),
            201
        );
    }

    public function show(string $id)
    {
        $video = Video::with('user')->find($id);
        if(!$video) {
            return $this->handleErrorResponse(
                'Vídeo não encontrado.',
                null,
                404
            );
        }

        return $this->handleSuccessResponse(
            'Vídeo obtido com sucesso.',
            new VideoResource($video)
        );
    }

    public function comentarios(string $id)
    {
        $video = Video::find($id);
        if(!$video) {
            return $this->handleErrorResponse(
                'Vídeo não encontrado.',
                null,
                404
            );
        }

        return $this->getCommentsForModel($video);
    }
}
