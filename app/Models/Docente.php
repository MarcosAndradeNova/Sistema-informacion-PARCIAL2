<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Docente extends Model
{
    protected $table = 'docente';
    protected $primaryKey = 'ciusuario';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'ciusuario',
        'coddocente',
        'profesion',
        'nivelformacion',
        'experiencia',
        'codrol',
        'estado',
        'idmateria'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'ciusuario', 'ci');
    }

    public function materia()
    {
        return $this->belongsTo(Materia::class, 'idmateria', 'id');
    }
}
