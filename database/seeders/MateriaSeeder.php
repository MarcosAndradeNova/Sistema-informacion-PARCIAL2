<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MateriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $materias = [
            ['nombre' => 'Matemáticas', 'estado' => 'HABILITADO'],
            ['nombre' => 'Física', 'estado' => 'HABILITADO'],
            ['nombre' => 'Inglés', 'estado' => 'HABILITADO'],
            ['nombre' => 'Computación', 'estado' => 'HABILITADO'],
        ];

        foreach ($materias as $materia) {
            \App\Models\Materia::firstOrCreate(['nombre' => $materia['nombre']], $materia);
        }
    }
}
