<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscribe extends Model
{
    use HasFactory;

    protected $table = 'inscribe';
    public $incrementing = false;
    protected $primaryKey = ['codigo_post', 'codigo_carrera'];
    public $timestamps = false;

    protected $fillable = [
        'codigo_post',
        'codigo_carrera',
        'opcion'
    ];
}
