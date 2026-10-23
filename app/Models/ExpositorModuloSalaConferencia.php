<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpositorModuloSalaConferencia extends Model
{
    protected $table = 'expositor_modulo_sala_conferencia';
    use SoftDeletes;

    protected $fillable = [
        'id',
        'id_exposicion',
        'id_modulo_sala_conferencia',
        'id_expositor',
        'status',
        'temario',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    function expositor() {
        return $this->belongsTo(Expositor::class, 'id_expositor')->withTrashed();
    }

    function exposicion() {
        return $this->belongsTo(Exposicion::class, 'id_exposicion');
    }

    function moduloSalaConferencia() {
        return $this->belongsTo(ModuloSalaConferencia::class, 'id_modulo_sala_conferencia');
    }

    function expositorModuloSalaConferenciaDisertante() {
        return $this->hasMany(ExpositorModuloSalaConferenciaDisertante::class, 'id_expositor_modulo_sala_conferencia');
    }
}
