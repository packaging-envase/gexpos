<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notificacion extends Model
{
    protected $table = 'notificacion';
    use SoftDeletes;

    protected $fillable = [
        'descripcion'
    ];
}