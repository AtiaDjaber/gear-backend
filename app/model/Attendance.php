<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    protected $casts = [
        'created_at' => 'datetime:Y-d-m H:i:s', // Change your format
        'updated_at' => 'datetime:Y-d-m H:i:s',
        'date' => 'datetime:Y-d-m H:i:s',
    ];
}
