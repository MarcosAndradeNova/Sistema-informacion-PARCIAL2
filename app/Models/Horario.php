<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    protected $table = 'horario';
    public $timestamps = false;

    protected $fillable = [
        'dia',
        'iniciohorario',
        'finhorario',
        'nroaula'
    ];

    public function aula()
    {
        return $this->belongsTo(Aula::class, 'nroaula', 'nro');
    }

    public function grupodocente()
    {
        return $this->hasOne(GrupoDocente::class, 'idhorario', 'id');
    }
}
