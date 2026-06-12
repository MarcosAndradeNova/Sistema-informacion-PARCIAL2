<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Postulante extends Model
{
    use HasFactory;

    protected $table = 'postulante';
    protected $primaryKey = 'ciusuario';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    // Definimos los campos que se pueden llenar masivamente en el modelo
    protected $fillable = [
        'ciusuario',
        'rude',
        'colegioprocedencia',
        'estadodocum',
        'titulobachiller',
        'ciudad'
    ];

    // Relación de un postulante con su usuario principal en la base
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'ciusuario', 'ci');
    }

    public function postulaciones()
    {
        return $this->hasMany(Postulacion::class, 'ciusuario', 'ciusuario');
    }

    public function grupos()
    {
        return $this->belongsToMany(Grupo::class, 'postulacion', 'ciusuario', 'codgrupo', 'ciusuario', 'codigo');
    }
}
