<?php

namespace App\Providers;

use App\Models\Comentario;
use App\Models\Post;
use App\Models\User;
use App\Models\Video;
use App\Policies\ComentarioPolicy;
use App\Policies\PostPolicy;
use App\Policies\UserPolicy;
use App\Policies\VideoPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Comentario::class => ComentarioPolicy::class,
        User::class => UserPolicy::class,
        Post::class => PostPolicy::class,
        Video::class => VideoPolicy::class,
    ];

    public function boot(): void
    {
        //
    }
}
