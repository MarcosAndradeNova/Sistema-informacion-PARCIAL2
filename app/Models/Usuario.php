<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;

    protected $table = 'usuarios';
    protected $primaryKey = 'ci';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = true;

    protected $fillable = [
        'ci',
        'nombres',
        'apellidos',
        'fecha_nacimiento',
        'sexo',
        'direccion',
        'telefono',
        'correo_electronico',
        'password',
        'rol',
    ];

    public function postulante()
    {
        return $this->hasOne(Postulante::class, 'ci_usuario', 'ci');
    }
}
