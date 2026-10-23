<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModuloPrensa extends Model
{
    protected $table = 'modulo_prensa';
    use SoftDeletes;

    protected $fillable = [
        'aprobado',
        'titulo',
        'texto',
        'contacto'
    ];

    public function moduloImagen() {
        return $this->hasMany(ModuloPrensaImagen::class, 'id_modulo_prensa');
    }

    public function moduloArchivo() {
        return $this->hasOne(ModuloPrensaArchivo::class, 'id_modulo_prensa');
    }
}