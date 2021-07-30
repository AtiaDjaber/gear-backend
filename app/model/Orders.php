<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    protected $fillable = [
        'addressFrom', 'addressTo', 'status', 'description',
        'fromLatitude', 'fromLongutide', 'toLatitude', 'toLongutide',
        "user_id", "photo", "typeTransport",
        'distance', 'duration'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function driver()
    {
        return $this->belongsTo('App\Driver');
    }

    public function offer()
    {
        return $this->hasMany('App\model\Offer');
    }
}
