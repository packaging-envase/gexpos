<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpositorModuloPlanoStandFile extends Model
{
    protected $table = 'expositor_modulo_plano_stand_file';
    use SoftDeletes;

    protected $fillable = [
        'id',
        'id_exp_mod_plano_stand',
        'path',
        'name',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    function expositorModuloPlanoStand() {
        return $this->belongsTo(ExpositorModuloPlanoStand::class, 'id_exp_mod_plano_stand');
    }
}
