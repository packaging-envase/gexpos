<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CongresoModuloLocation extends Model
{
    protected $table = 'congreso_modulo_location';
    use SoftDeletes;
}