<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class asd extends Model
{
    protected $table = 'asd';
    use SoftDeletes;

    protected $fillable = [
        'id',
    ];
}