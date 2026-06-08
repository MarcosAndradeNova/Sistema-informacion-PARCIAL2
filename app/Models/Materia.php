<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Materia extends Model
{
    protected $fillable = [
        'nombre',
        'puntos',
        'docente_ci',
        'temario_avance',
        'enlaces_material'
    ];
}
