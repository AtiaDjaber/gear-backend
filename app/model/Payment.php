<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    protected $casts = [
        'created_at' => 'datetime:d-m-Y H:i:s', // Change your format
        'updated_at' => 'datetime:d-m-Y H:i:s',
        'date' => 'datetime:d-m-Y H:i:s',
    ];
}
