<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permiso extends Model
{
    protected $table = 'permiso';
    use SoftDeletes;

    protected $fillable = [
        'id',
        'codigo',
        'descripcion',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
