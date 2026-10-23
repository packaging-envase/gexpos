<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmailPredefinidoToExpositor extends Model
{
    protected $table = 'email_predefinido_to_expositor';
    use SoftDeletes;
}