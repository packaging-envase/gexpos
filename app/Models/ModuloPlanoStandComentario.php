<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModuloPlanoStandComentario extends Model
{
    protected $table = 'modulo_plano_stand_comentario';
    use SoftDeletes;

    protected $fillable = [
        'id',
        'id_expositor',
        'id_exp_mod_plano_stand',
        'descripcion',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    function expositor() {
        return $this->belongsTo(Exposicion::class, 'id_expositor');
    }

    function expositorModuloPlanoStand() {
        return $this->belongsTo(ExpositorModuloPlanoStand::class, 'id_exp_mod_plano_stand');
    }
}
