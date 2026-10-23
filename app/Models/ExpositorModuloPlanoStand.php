<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpositorModuloPlanoStand extends Model
{
    protected $table = 'expositor_modulo_plano_stand';
    use SoftDeletes;

    protected $fillable = [
        'id',
        'id_exposicion',
        'id_expositor',
        'id_modulo_plano_stand',
        'status',
        'descripcion',
        'nombre',
        'apellido',
        'telefono',
        'email',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    function expositor() {
        return $this->belongsTo(Expositor::class, 'id_expositor');
    }

    function exposicion() {
        return $this->belongsTo(Exposicion::class, 'id_exposicion');
    }

    function moduloPlanoStand() {
        return $this->belongsTo(ModuloPlanoStand::class, 'id_modulo_plano_stand');
    }

    function expositorModuloPlanoStandFile() {
        return $this->hasMany(ExpositorModuloPlanoStandFile::class, 'id_exp_mod_plano_stand');
    }

    function moduloPlanoStandComentario() {
        return $this->hasMany(ModuloPlanoStandComentario::class, 'id_exp_mod_plano_stand');
    }
}
