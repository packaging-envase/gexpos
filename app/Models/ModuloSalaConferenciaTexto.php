<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModuloSalaConferenciaTexto extends Model
{
    protected $table = 'modulo_sala_conferencia_texto';
    use SoftDeletes;

    protected $fillable = [
        'id',
        'id_exposicion',
        'descripcion',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    function exposicion() {
        return $this->belongsTo(Exposicion::class, 'id_exposicion');
    }
}
