<?php

namespace App\Models;

use App\Policies\VideoPolicy;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['title', 'url', 'description', 'user_id'])]
#[UsePolicy(VideoPolicy::class)]
class Video extends Model
{
    use HasFactory, SoftDeletes;
    
    public function comentarios(): MorphMany
    {
        return $this->morphMany(Comentario::class, 'commentable')->chaperone();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}