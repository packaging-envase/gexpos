<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpositorModuloElectricidad extends Model
{
    protected $table = 'expositor_modulo_electricidad';
    use SoftDeletes;

    protected $fillable = [
        'id',
        'id_exposicion',
        'id_expositor',
        'tiene_maquinas',
        'peso_medida',
        'funcionan',
        'monofacsica',
        'trifasica',
        'apellido_nombre',
        'cargo',
        'telefono',
        'celular',
        'email',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    function expositor() {
        return $this->belongsTo(Expositor::class, 'id_expositor');
    }
}
