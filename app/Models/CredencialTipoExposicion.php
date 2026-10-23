<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CredencialTipoExposicion extends Model
{
    protected $table = 'credencial_tipo_exposicion';
    use SoftDeletes;

    protected $fillable = [
        'id',
        'activa',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function credencialTipo() {
        return $this->belongsTo(CredencialTipo::class, 'id_credencial_tipo');
    }
}