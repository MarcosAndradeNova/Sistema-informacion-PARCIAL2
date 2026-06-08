<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Postulante extends Model
{
    use HasFactory;

    protected $table = 'postulante';
    protected $primaryKey = 'ci_usuario';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    // Definimos los campos que se pueden llenar masivamente en el modelo
    protected $fillable = [
        'ci_usuario',
        'rude',
        'colegio_proc',
        'tit_bachiller_nro',
        'ciudad',
        'estado_admision',
        'observaciones_documentos',
        'grupo_id'
    ];

    // Relación de un postulante con su usuario principal en la base
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'ci_usuario', 'ci');
    }

    // Un postulante tiene muchas calificaciones asignadas en diferentes materias
    public function calificaciones()
    {
        return $this->hasMany(Calificacion::class, 'ci_usuario', 'ci_usuario');
    }

    public function primeraOpcion()
    {
        return $this->belongsTo(Carrera::class, 'carrera_primera_opcion');
    }

    public function segundaOpcion()
    {
        return $this->belongsTo(Carrera::class, 'carrera_segunda_opcion');
    }

    public function grupo()
    {
        return $this->belongsTo(Grupo::class, 'grupo_id');
    }
}
