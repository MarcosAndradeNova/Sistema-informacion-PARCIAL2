<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Carrera;
use Illuminate\Support\Facades\DB;

class CarreraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Limpiar la tabla si es necesario o ignorar duplicados
        $carreras = [
            ['codigo' => 'SIS', 'nombre' => 'Ingeniería en Sistemas'],
            ['codigo' => 'INF', 'nombre' => 'Ingeniería Informática'],
            ['codigo' => 'RED', 'nombre' => 'Ingeniería en Redes'],
            ['codigo' => 'ROB', 'nombre' => 'Ingeniería en Robótica'],
        ];

        foreach ($carreras as $carrera) {
            // Usamos updateOrCreate para evitar duplicados si ya existen
            Carrera::updateOrCreate(
                ['codigo' => $carrera['codigo']],
                ['nombre' => $carrera['nombre']]
            );
        }
    }
}
