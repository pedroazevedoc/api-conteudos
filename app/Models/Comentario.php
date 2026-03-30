<?php

namespace App\Models;

use App\Policies\ComentarioPolicy;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['content', 'user_id', 'commentable_id', 'commentable_type'])]
#[UsePolicy(ComentarioPolicy::class)]
class Comentario extends Model
{
    use HasFactory, SoftDeletes;
    
    public function commentable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
