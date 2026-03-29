<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    // Apenas admins podem listar usuários
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin';
    }

    // Apenas admins ou o próprio usuário podem ver os detalhes de um usuário
    public function view(User $user, User $target): bool
    {
        return $user->role === 'admin' || $user->id === $target->id;
    }
}
