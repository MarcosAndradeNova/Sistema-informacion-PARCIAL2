<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;

    protected $table = 'usuario';
    protected $primaryKey = 'ci';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'ci',
        'nombre',
        'apellidopat',
        'apellidomat',
        'nacionalidad',
        'sexo',
        'fechanac',
        'email',
        'telefono',
        'direccion',
        'tipo'
    ];

    public function postulante()
    {
        return $this->hasOne(Postulante::class, 'ciusuario', 'ci');
    }

    public function gruposComoDocente()
    {
        return $this->hasMany(GrupoDocente::class, 'ciusuario', 'ci');
    }
}
