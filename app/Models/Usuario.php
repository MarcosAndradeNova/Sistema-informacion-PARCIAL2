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
        'apellido_pat',
        'apellido_mat',
        'fechanac',
        'email',
        'telefono',
        'direccion',
        'sexo',
        'tipo',
        'nacionalidad'
    ];

    public function postulante()
    {
        return $this->hasOne(Postulante::class, 'ci_usuario', 'ci');
    }
}
