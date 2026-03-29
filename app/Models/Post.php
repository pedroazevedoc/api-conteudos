<?php

namespace App\Models;

use App\Policies\PostPolicy;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['title', 'content', 'user_id'])]
#[UsePolicy(PostPolicy::class)]
class Post extends Model
{
    use SoftDeletes;

    public function comentarios(): MorphMany
    {
        return $this->morphMany(Comentario::class, 'commentable')->chaperone();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
