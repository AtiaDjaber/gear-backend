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

    public function Product()
    {
        return $this->belongsTo(Product::class);
    }

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s', // Change your format
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'date' => 'datetime:Y-m-d H:i:s',
    ];
}
