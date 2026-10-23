<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModuloServiciosOptativos extends Model
{
    protected $table = 'modulo_servicios_optativos';
    use SoftDeletes;

    protected $fillable = [
        'id',
        'id_exposicion',
        'nombre',
        'descripcion',
        'active',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    function exposicion() {
        return $this->belongsTo(Exposicion::class, 'id_exposicion');
    }

    function moduloServiciosOptativosFile() {
        return $this->hasMany(ModuloServiciosOptativosFile::class, 'id_mod_serv_opt');
    }
}
