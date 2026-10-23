<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModuloPrensaImagen extends Model
{
    protected $table = 'modulo_prensa_imagen';
    use SoftDeletes;

    protected $fillable = [
        'id_modulo_prensa',
        'path'
    ];
}