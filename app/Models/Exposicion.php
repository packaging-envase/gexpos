<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exposicion extends Model
{
    protected $table = 'exposicion';
    use SoftDeletes;

    protected $fillable = [
        'id',
        'nombre',
        'fecha_inicio',
        'fecha_fin',
        'fecha_inicio',
        'email_expositor',
        'email_administrador_gexpo',
        'mapa',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function planoStand() {
        return $this->hasOne(ModuloPlanoStand::class, 'id_exposicion');
    }

    public function invitacion() {
        return $this->hasOne(ModuloInvitacion::class, 'id_exposicion');
    }

    public function catalogo() {
        return $this->hasOne(ModuloCatalogo::class, 'id_exposicion');
    }

    public function electricidad() {
        return $this->hasOne(ModuloElectricidad::class, 'id_exposicion');
    }

    public function salaConferencia() {
        return $this->hasMany(ModuloSalaConferencia::class, 'id_exposicion');
    }

    public function salaConferenciaTexto() {
        return $this->hasOne(ModuloSalaConferenciaTexto::class, 'id_exposicion');
    }

    public function serviciosOptativos() {
        return $this->hasMany(ModuloServiciosOptativos::class, 'id_exposicion')->orderBy('nombre');
    }

    public function exposicionArchivo() {
        return $this->hasMany(ExposicionArchivo::class, 'id_exposicion');
    }
}