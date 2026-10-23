<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StandTipo extends Model
{
    protected $table = 'stand_tipo';
    use SoftDeletes;

}