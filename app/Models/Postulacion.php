<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Postulacion extends Model
{
    use HasFactory;

    protected $table = 'postulacion';
    protected $primaryKey = 'cod_postulacion';
    public $incrementing = false;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'cod_postulacion',
        'estado_doc',
        'fecha',
        'hora',
        'ci_usuario',
        'id_pago',
        'id_sem',
        'cod_grupo',
        'id_admision',
        'cod_rol'
    ];
}
