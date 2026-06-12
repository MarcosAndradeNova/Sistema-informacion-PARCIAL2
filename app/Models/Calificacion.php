<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calificacion extends Model
{
    use HasFactory;

    protected $table = 'calificaciones_postulante';

    protected $fillable = [
        'ciusuario',
        'materia',
        'nota1',
        'nota2',
        'nota3',
        'promedio',
        'estado'
    ];

    public function postulante()
    {
        return $this->belongsTo(Postulante::class, 'ciusuario', 'ciusuario');
    }
}
