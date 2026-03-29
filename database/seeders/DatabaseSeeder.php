<?php

namespace Database\Seeders;

use App\Models\User;
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
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@email.com',
                'password' => Hash::make(123456)
            ],
            [
                'name' => 'Usuário',
                'email' => 'user@email.com',
                'password' => Hash::make(123456)
            ]
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
