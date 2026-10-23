<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;


class Expositor extends Authenticatable
{
    use SoftDeletes;

    protected $table = 'expositor';

    protected $fillable = [
        'id',
        'id_exposicion',
        'id_empresa',
        'usuario',
        'password',
        'acepto_terminos_condiciones',
        'usuario_password_enviado',
        'status',
        'remember_token',
        'created_at',
        'updated_at',
        'deleted_at'
    ];


    public function exposicion() {
        return $this->belongsTo(Exposicion::class, 'id_exposicion');
    }

    public function empresa() {
        return $this->belongsTo(Empresa::class, 'id_empresa');
    }

    public function stand() {
        return $this->hasMany(Stand::class, 'id_expositor', 'id');
    }

    public function categoriasEmpresa() {
        return $this->hasMany(CategoriasEmpresa::class, 'id_empresa','id_empresa');
    }

    public function moduloElectricidad() {
        return $this->hasOne(ExpositorModuloElectricidad::class, 'id_expositor');
    }

    public function expositorModuloCatalogo() {
        return $this->hasOne(ExpositorModuloCatalogo::class, 'id_expositor');
    }

    public function depositoGarantia() {
        return $this->hasOne(ExpositorModuloDepositoGarantia::class, 'id_expositor');
    }
}