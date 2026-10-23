<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModuloPlanoStand extends Model
{
    protected $table = 'modulo_plano_stand';
    use SoftDeletes;

    protected $fillable = [
        'id',
        'id_exposicion',
        'descripcion',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    function exposicion() {
        return $this->belongsTo(Exposicion::class, 'id_exposicion');
    }

    function expositorModuloPlanoStand() {
        return $this->hasMany(ExpositorModuloPlanoStand::class, 'id_modulo_plano_stand');
    }
}
