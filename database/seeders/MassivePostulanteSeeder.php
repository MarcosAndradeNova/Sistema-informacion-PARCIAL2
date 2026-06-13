<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class MassivePostulanteSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('es_ES');
        $password = Hash::make('password123');
        $roles = DB::table('rol')->pluck('cod')->toArray();
        $codrol = !empty($roles) ? $roles[0] : null;

        if (!$codrol) {
            DB::table('rol')->insert(['cod' => 'R01', 'descripcion' => 'Postulante']);
            $codrol = 'R01';
        }

        $carreras = DB::table('carrera')->pluck('codigo')->toArray();
        if (empty($carreras)) {
            echo "No hay carreras en la BD. \n";
            return;
        }

        // Crear Admisión si no hay
        $admision = DB::table('admision')->first();
        if (!$admision) {
            DB::table('admision')->insert(['id' => 1, 'estado' => 'Activa']);
            $admisionId = 1;
        } else {
            $admisionId = $admision->id;
        }

        // Crear 2 semestres anteriores (Gestión 2024, 2025)
        $semestres = [];
        // Actual
        $semestres[] = DB::table('semestre')->first()->id ?? 1;
        
        // Anteriores
        if (!DB::table('semestre')->where('id', 2)->exists()) {
            DB::table('semestre')->insert(['id' => 2, 'semestre' => 2, 'año' => 2024]);
        }
        $semestres[] = 2;
        
        if (!DB::table('semestre')->where('id', 3)->exists()) {
            DB::table('semestre')->insert(['id' => 3, 'semestre' => 1, 'año' => 2025]);
        }
        $semestres[] = 3;

        // Materias para notas
        $materias = DB::table('materia')->pluck('id')->toArray();
        
        $grupos = DB::table('grupo')->pluck('codigo')->toArray();

        $ciBase = 30000000;
        $count = 0;

        $pagoIdCounter = DB::table('pago')->max('id') ?? 0;
        $codpostCounter = DB::table('postulacion')->max('codpost') ?? 0;

        foreach ($semestres as $semestreId) {
            echo "Generando 100 postulantes para Semestre ID: $semestreId...\n";
            
            for ($i = 0; $i < 100; $i++) {
                $ci = (string)($ciBase + $count);
                $email = "estudiante{$ci}@test.com";
                $count++;

                // 1. Usuario
                DB::table('usuario')->insert([
                    'ci' => $ci,
                    'nombre' => $faker->firstName,
                    'apellidopat' => $faker->lastName,
                    'apellidomat' => $faker->lastName,
                    'nacionalidad' => 'Boliviana',
                    'sexo' => $faker->randomElement(['M', 'F']),
                    'fechanac' => $faker->dateTimeBetween('-25 years', '-18 years')->format('Y-m-d'),
                    'email' => $email,
                    'telefono' => $faker->numerify('7#######'),
                    'direccion' => $faker->address,
                    'tipo' => 'P'
                ]);

                // 2. User (Auth)
                DB::table('users')->insert([
                    'name' => 'Estudiante ' . $ci,
                    'email' => $email,
                    'password' => $password,
                    'role' => 'postulante',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // 3. Postulante
                DB::table('postulante')->insert([
                    'ciusuario' => $ci,
                    'estadodocum' => 'INSCRITO'
                ]);

                // 4. Pago
                $pagoIdCounter++;
                DB::table('pago')->insert([
                    'id' => $pagoIdCounter,
                    'numerorecibo' => 'REC-' . $ci,
                    'monto' => 350,
                    'metodopago' => 'Transferencia',
                    'estado' => 'Completado',
                    'fecha' => now()->subDays(rand(1, 100))->toDateString(),
                    'ciusuario' => $ci
                ]);

                // 5. Postulacion
                $codpostCounter++;
                DB::table('postulacion')->insert([
                    'codpost' => $codpostCounter,
                    'idsemestre' => $semestreId,
                    'idadmision' => $admisionId,
                    'codrol' => $codrol,
                    'ciusuario' => $ci,
                    'idpago' => $pagoIdCounter,
                    'fecha' => now()->subDays(rand(1, 100))->toDateString(),
                    'hora' => now()->format('H:i:s'),
                    'estado_admision' => $faker->randomElement(['PENDIENTE', 'APROBADO_PENDIENTE', 'ADMITIDO', 'REPROBADO'])
                ]);

                // 6. Inscribe (Opciones de carrera)
                $carrera1 = $faker->randomElement($carreras);
                $carrera2 = $faker->randomElement(array_diff($carreras, [$carrera1])) ?? $carrera1;

                DB::table('inscribe')->insert([
                    'codigocarrera' => $carrera1,
                    'opcion' => 1,
                    'codpost' => $codpostCounter
                ]);
                
                DB::table('inscribe')->insert([
                    'codigocarrera' => $carrera2,
                    'opcion' => 2,
                    'codpost' => $codpostCounter
                ]);

                // 7. Calificación (Notas random)
                if (!empty($materias) && count($materias) > 0) {
                    $matId = $materias[0];
                    for ($examNro = 1; $examNro <= 3; $examNro++) {
                        $calificacion = rand(10, 100);
                        DB::table('resultadoexam')->insert([
                            'nroexamen' => $examNro,
                            'ciusuario' => $ci,
                            'codigogrupo' => 1,
                            'idmateria' => $matId,
                            'codpost' => $codpostCounter,
                            'calificacion' => $calificacion
                        ]);
                    }
                }
            }
        }
        echo "¡Se insertaron $count postulantes exitosamente!\n";
    }
}
