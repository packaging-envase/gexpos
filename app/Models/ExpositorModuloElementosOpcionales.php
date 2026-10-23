<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpositorModuloElementosOpcionales extends Model
{
    protected $table = 'expositor_modulo_elementos_opcionales';
    use SoftDeletes;

    protected $fillable = [
        'id',
        'id_modulo_elem_op',
        'id_expositor',
        'observacion'
    ];

    function exposicion() {
        return $this->belongsTo(ModuloElementosOpcionales::class, 'id_modulo_elem_op');
    }
}