<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModuloDepositoGarantia extends Model
{
    protected $table = 'modulo_deposito_garantia';
    use SoftDeletes;

    protected $fillable = [
        'id_exposicion',
        'descripcion',
        'fecha_cierre',
        'path'
    ];
}