<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExposicionArchivo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'exposicion_archivo';

    protected $fillable = [
        'id_exposicion',
        'original_name',
        'tipo',
        'clase',
        'path_muestra',
        'path',
        'pertenece_envase',
        'importante',
        'nivel'
    ];

    public function exposicion()
    {
        return $this->belongsTo(Exposicion::class, 'id_exposicion');
    }
}
