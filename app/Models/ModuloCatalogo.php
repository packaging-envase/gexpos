<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModuloCatalogo extends Model
{
    protected $table = 'modulo_catalogo';
    use SoftDeletes;

    protected $fillable = [
        'id',
        'id_exposicion',
        'max_file_size',
        'max_quantity_productos',
        'max_caracteres_producto_descripcion',
        'max_caracteres_empresa',
    ];

    function exposicion() {
        return $this->belongsTo(Exposicion::class, 'id_exposicion');
    }
}