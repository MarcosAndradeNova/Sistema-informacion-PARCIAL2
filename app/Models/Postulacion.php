<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Postulacion extends Model
{
    use HasFactory;

    protected $table = 'postulacion';
    protected $primaryKey = 'codpost';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'codpost',
        'fecha',
        'hora',
        'idpago',
        'idadmision',
        'ciusuario',
        'codrol',
        'codgrupo',
        'idsemestre',
        'promedio',
        'estado_admision',
        'carrera_admitida'
    ];

    public function grupo()
    {
        return $this->belongsTo(Grupo::class, 'codgrupo', 'codigo');
    }

    public function postulante()
    {
        return $this->belongsTo(Postulante::class, 'ciusuario', 'ciusuario');
    }
}
