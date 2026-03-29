<?php

namespace App\Policies;

use App\Models\User;

class VideoPolicy
{
    // Apenas usuários com permissão 'video-store' podem criar videos
    public function create(User $user): bool
    {
        return $user->can('video-store');
    }
}
