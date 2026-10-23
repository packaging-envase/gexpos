<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpositorModuloSalaConferenciaDisertante extends Model
{
    protected $table = 'expositor_modulo_sala_conferencia_disertante';
    use SoftDeletes;

    function expositorModuloSalaConferencia() {
        return $this->belongsTo(ExpositorModuloSalaConferencia::class, 'id_expositor_modulo_sala_conferencia');
    }
}
