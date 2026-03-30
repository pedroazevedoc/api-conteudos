<?php

namespace Database\Seeders;

use App\Models\Comentario;
use App\Models\Post;
use App\Models\User;
use App\Models\Video;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Usuários padrão
        $users = [
            [ 'name' => 'Admin',   'email' => 'admin@email.com', 'password' => Hash::make(123456), 'role' => 'admin' ],
            [ 'name' => 'Usuário', 'email' => 'user@email.com',  'password' => Hash::make(123456), 'role' => 'user' ]
        ];

        // Criar usuários padrão se não existirem
        foreach ($users as $user) {
            if (!User::where('email', $user['email'])->exists()) {
                User::create($user);
            }
        }

        // Factories
        User::factory(10)->create();
        
        // Criar 10 posts com 3 comentários cada
        Post::factory(10)->create()->each(function ($post) {
            Comentario::factory(3)->forPost($post)->create();
        });
        
        // Criar 10 vídeos com 3 comentários cada
        Video::factory(10)->create()->each(function ($video) {
            Comentario::factory(3)->forVideo($video)->create();
        });
    }
}
