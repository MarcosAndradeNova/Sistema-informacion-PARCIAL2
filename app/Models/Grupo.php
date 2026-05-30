<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    protected $table = 'grupos';

    protected $fillable = [
        'nombre',
        'capacidad',
        'estado'
    ];

    public function postulantes()
    {
        return $this->hasMany(Postulante::class, 'grupo_id');
    }

    public function docentes()
    {
        return $this->belongsToMany(Usuario::class, 'grupo_docente_materia', 'grupo_id', 'ci_docente')
                    ->withPivot('materia')
                    ->withTimestamps();
    }
}
