<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModuloSalaConferencia extends Model
{
    protected $table = 'modulo_sala_conferencia';
    use SoftDeletes;

    protected $fillable = [
        'id',
        'id_exposicion',
        'sala',
        'fecha',
        'duracion',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    function exposicion() {
        return $this->belongsTo(Exposicion::class, 'id_exposicion');
    }

    function expositorModuloSalaConferencia() {
        return $this->hasMany(ExpositorModuloSalaConferencia::class, 'id_modulo_sala_conferencia');
    }
}
