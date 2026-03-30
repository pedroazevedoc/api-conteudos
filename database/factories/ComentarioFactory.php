<?php

namespace Database\Factories;

use App\Models\Comentario;
use App\Models\Post;
use App\Models\User;
use App\Models\Video;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Comentario>
 */
class ComentarioFactory extends Factory
{
    public function definition(): array
    {
        return [
            'content' => fake()->sentence(),
            'user_id' => User::factory(),
            'commentable_id' => null,
            'commentable_type' => null,
        ];
    }

    public function forPost(Post $post): static
    {
        return $this->state([
            'commentable_id' => $post->id,
            'commentable_type' => Post::class,
        ]);
    }

    public function forVideo(Video $video): static
    {
        return $this->state([
            'commentable_id' => $video->id,
            'commentable_type' => Video::class,
        ]);
    }
}
