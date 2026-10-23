<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpositorModuloInvitacion extends Model
{
    protected $table = 'expositor_modulo_invitacion';
    use SoftDeletes;
}
