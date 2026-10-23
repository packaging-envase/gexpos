<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CredencialDetalle extends Model
{
    protected $table = 'credencial_detalle';
    use SoftDeletes;

    protected $fillable = [
        'id',
        'id_credencial_tipo_expositor',
        'nombre',
        'apellido',
        'empresa',
        'cargo',
        'email',
        'telefono'        
    ];
}