<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bitacora extends Model
{
    protected $table = 'bitacora';
    public $timestamps = false; // Manejaremos fecha y hora manualmente según la tabla

    protected $fillable = [
        'accion',
        'fecha',
        'hora',
        'ip',
        'ciusuario'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'ciusuario', 'ci');
    }
}
