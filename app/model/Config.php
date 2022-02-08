<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;
use LevelyearsSubjs;

class Config extends Model
{
    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s', // Change your format
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'date' => 'datetime:Y-m-d H:i:s',
    ];
}
