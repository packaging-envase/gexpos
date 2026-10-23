<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NovedadesEmpresa extends Model
{
    protected $table = 'novedades_empresa';
    use SoftDeletes;

    protected $fillable = [
        'id',
        'id_exposicion',
        'id_empresa',
        'descripcion',
        'status'
    ];
}