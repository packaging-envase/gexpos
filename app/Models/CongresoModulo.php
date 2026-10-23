<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CongresoModulo extends Model
{
    protected $table = 'congreso_modulo';
    use SoftDeletes;

    protected $fillable = [
        'id',
    ];
}