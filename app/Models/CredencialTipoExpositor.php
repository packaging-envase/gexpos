<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CredencialTipoExpositor extends Model
{
    protected $table = 'credencial_tipo_expositor';
    use SoftDeletes;

    protected $fillable = [
        'id',
        'cantidad',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function credencialDetalle() {
        return $this->hasMany(CredencialDetalle::class, 'id_credencial_tipo_expositor');
    }
}