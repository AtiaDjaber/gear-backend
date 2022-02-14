<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $guarded = [];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i', // Change your format
        'updated_at' => 'datetime:Y-m-d H:i',
        'date' => 'datetime:Y-m-d',
    ];
}
