<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrensaAcreditacion extends Model
{
    protected $table = 'prensa_acreditacion';
    use SoftDeletes;

    protected $fillable = [
        'dni',
        'apellido_nombre',
        'whatsapp',
        'medio',
    ];
}