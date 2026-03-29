<?php

namespace App\Providers;

use App\Models\Comentario;
use App\Policies\ComentarioPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Comentario::class => ComentarioPolicy::class,
    ];

    public function boot(): void
    {
        //
    }
}
