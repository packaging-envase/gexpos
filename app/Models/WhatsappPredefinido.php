<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WhatsappPredefinido extends Model
{
    protected $table = 'whatsapp_predefinido';
    use SoftDeletes;
}