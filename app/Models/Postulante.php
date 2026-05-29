<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Postulante extends Model
{
    use HasFactory;

    protected $table = 'postulantes';
    protected $primaryKey = 'ci_usuario';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = true;

    protected $fillable = [
        'ci_usuario',
        'carrera_primera_opcion',
        'carrera_segunda_opcion',
        'colegio_procedencia',
        'ciudad',
        'titulo_bachiller',
        'otros_requisitos',
        'pago_efectuado',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'ci_usuario', 'ci');
    }

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
}
