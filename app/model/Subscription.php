<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s', // Change your format
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'date' => 'datetime:Y-m-d',
    ];
    public function Product()
    {
        return $this->belongsTo(Product::class);
    }

    public function Client()
    {
        return $this->belongsTo(Client::class);
    }
    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
