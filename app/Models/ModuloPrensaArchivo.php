<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModuloPrensaArchivo extends Model
{
    protected $table = 'modulo_prensa_archivo';
    use SoftDeletes;

    protected $fillable = [
        'id_modulo_prensa',
        'path'
    ];
}