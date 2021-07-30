<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{


    protected  $table = "offer";
    protected $fillable = [
        'driver_id', 'orders_id', 'price'
    ];
    public function drivers()
    {
        return $this->belongsTo('App\Driver');
    }
    public function orders()
    {
        return $this->belongsTo('App\model\Orders');
    }
}
