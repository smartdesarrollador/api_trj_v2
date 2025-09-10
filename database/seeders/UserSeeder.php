<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'id' => 1,
                'name' => "admin",
                'email' => "admin@admin.com",
                'email_verified_at' => null,
                'password' => Hash::make('Admin2025$'),
                'rol' => 'administrador',
                'remember_token' => null,
            ],
            [
                'id' => 2,
                'name' => "Jacky",
                'email' => "jacky@testcorreo.com",
                'email_verified_at' => null,
                'password' => Hash::make('1234567'),
                'rol' => 'autor',
                'remember_token' => null,
            ],
            [
                'id' => 3,
                'name' => "Tomas",
                'email' => "tomas@testcorreo.com",
                'email_verified_at' => null,
                'password' => Hash::make('peru123'),
                'rol' => 'autor',
                'remember_token' => null,
            ]
        ];

        foreach ($users as $user) {
            // Verificar si ya existe el usuario con ese email
            if (!DB::table('users')->where('email', $user['email'])->exists()) {
                DB::table('users')->insert($user);
            } else {
                // Actualizar el usuario existente
                DB::table('users')
                    ->where('email', $user['email'])
                    ->update([
                        'name' => $user['name'],
                        'password' => $user['password'],
                        'rol' => $user['rol'],
                    ]);
            }
        }
    }
}
