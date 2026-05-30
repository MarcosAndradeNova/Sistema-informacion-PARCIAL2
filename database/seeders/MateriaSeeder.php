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
            ['nombre' => 'Matemáticas', 'puntos' => 25],
            ['nombre' => 'Física', 'puntos' => 25],
            ['nombre' => 'Inglés', 'puntos' => 25],
            ['nombre' => 'Computación', 'puntos' => 25],
        ];

        foreach ($materias as $materia) {
            \App\Models\Materia::firstOrCreate(['nombre' => $materia['nombre']], $materia);
        }
    }
}
