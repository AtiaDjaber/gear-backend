<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    //    protected $table="drivers";
    protected $fillable = [
        'name', 'tel', 'type'
    ];
    public function offers()
    {
        return $this->hasMany('App\model\Offer');
    }

    public function orders()
    {
        return $this->hasMany('App\model\Orders');
    }
}
