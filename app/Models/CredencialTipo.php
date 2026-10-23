<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CredencialTipo extends Model
{
    protected $table = 'credencial_tipo';
    use SoftDeletes;

    protected $fillable = [
        'id',
        'descripcion',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}