<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VideoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title'       => $this->title,
            'url'         => $this->url,
            'description' => $this->description,
            'user' => [
                'name'  => $this->user->name,
                'email' => $this->user->email
            ]
        ];
    }
}
