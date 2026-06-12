<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultadoExam extends Model
{
    use HasFactory;

    protected $table = 'resultadoexam';
    public $timestamps = false;
    
    // Al no tener una llave primaria definida como "id" de tipo auto-increment en el esquema
    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [
        'nroexamen',
        'ciusuario',
        'codigogrupo',
        'idmateria',
        'codpost',
        'calificacion'
    ];

    public function materia()
    {
        return $this->belongsTo(Materia::class, 'idmateria', 'id');
    }

    public function postulante()
    {
        return $this->belongsTo(Postulante::class, 'ciusuario', 'ciusuario');
    }
}
