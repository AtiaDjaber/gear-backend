<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Config extends Model
{
    // use SoftDeletes;
    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i', // Change your format
        'updated_at' => 'datetime:Y-m-d H:i',
        'date' => 'datetime:Y-m-d',
    ];
}
