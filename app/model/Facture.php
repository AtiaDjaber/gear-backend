<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Facture extends Model
{

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i', // Change your format
        'updated_at' => 'datetime:Y-m-d H:i',
        'date' => 'datetime:Y-m-d',
    ];
}
