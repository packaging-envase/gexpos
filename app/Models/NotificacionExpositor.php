<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NotificacionExpositor extends Model
{
    protected $table = 'notificacion_expositor';
    use SoftDeletes;

    protected $fillable = [
        'id_notificacion',
        'id_expositor',
        'leido'
    ];
}