<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $table = 'pago';
    protected $primaryKey = 'id';
    public $incrementing = false; // Manejaremos el ID manualmente por precaución
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'numero_recibo',
        'monto',
        'metodo_pago',
        'estado',
        'fecha',
        'ci_usuario',
    ];
}
