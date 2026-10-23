<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmpresaContacto extends Model
{
    protected $table = 'empresa_contacto';
    use SoftDeletes;

    protected $fillable = [
        'id',
        'id_empresa',
        'apellido_nombre',
        'cargo',
        'telefono',
        'email',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    function empresa() {
        return $this->belongsTo(Empresa::class, 'id_empresa', 'id');
    }
}
