<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class congreso extends Model
{
    protected $table = 'congreso';
    use SoftDeletes;
}