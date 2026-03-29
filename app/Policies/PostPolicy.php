<?php

namespace App\Policies;

use App\Models\User;

class PostPolicy
{
    // Apenas usuários com permissão 'post-store' podem criar posts
    public function create(User $user): bool
    {
        return $user->can('post-store');
    }
}
