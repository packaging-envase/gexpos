<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductoServicioEmpresa extends Model
{
    protected $table = 'producto_servicio_empresa';
    use SoftDeletes;

    protected $fillable = [
        'id',
        'id_empresa',
        'nombre',
        'descripcion',
        'path_logo',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    function empresa() {
        return $this->belongsTo(Empresa::class, 'id_empresa', 'id');
    }
}
