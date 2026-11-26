<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModuloElementosOpcionales extends Model
{
    protected $table = 'modulo_elementos_opcionales';
    use SoftDeletes;

    protected $fillable = [
        'id',
        'id_exposicion',
        'titulo',
        'descripcion'
    ];

    function exposicion() {
        return $this->belongsTo(Exposicion::class, 'id_exposicion');
    }

    function expositor() {
        return $this->havMany(ExpositorModuloElementosOpcionales::class, 'id_modulo_elem_op');
    }
}