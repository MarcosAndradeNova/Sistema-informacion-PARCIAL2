<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrupoDocente extends Model
{
    protected $table = 'grupodocente';
    public $incrementing = false; // no id column
    protected $primaryKey = null; // composite keys are not well supported for primaryKey, we set it to null
    public $timestamps = false;

    protected $fillable = [
        'codigogrupo',
        'ciusuario',
        'idmateria',
        'idhorario',
        'whatsapp_link'
    ];

    public function grupo()
    {
        return $this->belongsTo(Grupo::class, 'codigogrupo', 'codigo');
    }

    public function docente()
    {
        return $this->belongsTo(Usuario::class, 'ciusuario', 'ci');
    }

    public function materia()
    {
        return $this->belongsTo(Materia::class, 'idmateria', 'id');
    }

    public function horario()
    {
        return $this->belongsTo(Horario::class, 'idhorario', 'id');
    }
}
