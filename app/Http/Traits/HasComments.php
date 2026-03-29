<?php
namespace App\Http\Traits;

use App\Http\Resources\ComentarioResource;

trait HasComments
{
    public function getCommentsForModel($model)
    {
        return $this->handleSuccessResponse(
            'Comentários obtidos com sucesso.',
            ComentarioResource::collection($model->comentarios()->get())
        );
    }
}