<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpositorModuloCatalogo extends Model
{
    protected $table = 'expositor_modulo_catalogo';
    use SoftDeletes;

    protected $fillable = [
        'id',
        'id_expositor',
        'direccion',
        'telefono',
        'whatsapp',
        'email',
        'web'
    ];

    function expositor() {
        return $this->belongsTo(Expositor::class, 'id_expositor');
    }
}
