<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Variables extends Model
{
    protected $table = 'variables';
    use SoftDeletes;

    protected $fillable = [
        'id',
        'value_string',
        'value_integer',
        'value_date',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}