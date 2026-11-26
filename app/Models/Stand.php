<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stand extends Model
{
    protected $table = 'stand';
    use SoftDeletes;

    protected $fillable = [
        'id',
        'id_exposicion',
        'id_expositor',
        'numero',
        'status',
        'metros_cuadrados',
        'nombre_exposicion',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function exposicion() {
        return $this->belongsTo(Exposicion::class, 'id_exposicion');
    }

    public function expositor() {
        return $this->belongsTo(Expositor::class, 'id_expositor');
    }

}