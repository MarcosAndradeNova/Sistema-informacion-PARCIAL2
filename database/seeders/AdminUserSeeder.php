<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Creamos o actualizamos el usuario administrador inicial del sistema
        User::updateOrCreate(
            ['email' => 'admin@ficct.edu'],
            [
                'name' => 'Administrador FICCT',
                'password' => Hash::make('password123'),
            ]
        );
    }
}
