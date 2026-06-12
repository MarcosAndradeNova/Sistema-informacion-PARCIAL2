<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    protected $table = 'grupo';
    protected $primaryKey = 'codigo';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'nombre',
        'cupo',
        'idturno'
    ];

    public function postulaciones()
    {
        return $this->hasMany(Postulacion::class, 'codgrupo', 'codigo');
    }

    public function grupodocentes()
    {
        return $this->hasMany(GrupoDocente::class, 'codigogrupo', 'codigo');
    }

    public function postulantes()
    {
        return $this->belongsToMany(Postulante::class, 'postulacion', 'codgrupo', 'ciusuario', 'codigo', 'ciusuario');
    }
}
