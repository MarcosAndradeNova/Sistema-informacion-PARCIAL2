<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscribe extends Model
{
    use HasFactory;

    protected $table = 'inscribe';
    public $incrementing = false;
    // Definimos llaves primarias compuestas para la tabla inscribe
    protected $primaryKey = ['codigo_post', 'codigo_carrera'];
    public $timestamps = false;

    // Campos que pueden ser llenados masivamente en la inscripción
    protected $fillable = [
        'codigo_post',
        'codigo_carrera',
        'opcion'
    ];
}
