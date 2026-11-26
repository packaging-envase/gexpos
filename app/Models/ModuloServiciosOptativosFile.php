<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModuloServiciosOptativosFile extends Model
{
    protected $table = 'modulo_servicios_optativos_file';
    use SoftDeletes;

    protected $fillable = [
        'id',
        'id_mod_serv_opt',
        'path',
        'name',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    function moduloServiciosOptativos() {
        return $this->belongsTo(ModuloServiciosOptativos::class, 'id_mod_serv_opt');
    }
}
