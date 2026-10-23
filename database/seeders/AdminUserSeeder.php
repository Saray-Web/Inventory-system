<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $name = env('ADMIN_NAME');
        $email = env('ADMIN_EMAIL');
        $password = env('ADMIN_PASSWORD');
        if(empty($name) || empty($email) || empty($password)){
            throw new \RuntimeException('ADMIN_NAME , ADMIN_EMAIL , ADMIN_PASSWORD must be set in .env');
        }
        User::firstOrCreate(
            [
                'email' => $email,
            ],
            [
                'name' => $name,
                'password' => Hash::make($password),
            ]
        );
    }
}
