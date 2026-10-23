<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpositorModuloDepositoGarantia extends Model
{
    protected $table = 'expositor_modulo_deposito_garantia';
    use SoftDeletes;

    protected $fillable = [
        'id_exposicion',
        'id_expositor',
        'apellido',
        'nombre',
        'cobrado',
        'path',
        'fecha_devolucion',
    ];
}