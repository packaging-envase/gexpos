<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CongresoModuloDisertante extends Model
{
    protected $table = 'congreso_modulo_disertante';
    use SoftDeletes;

    protected $fillable = [
        'id',
    ];
}