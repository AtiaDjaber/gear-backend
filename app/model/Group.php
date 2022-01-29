<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;
use LevelyearsSubjs;

class Group extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s', // Change your format
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'date' => 'datetime:Y-m-d H:i:s',
    ];
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function sessions()
    {
        return $this->hasMany(Session::class);
    }

    public function Client()
    {
        return $this->belongsTo(Client::class, "Client_id");
    }
    public function subj()
    {
        return $this->belongsTo(Subj::class, "subj_id");
    }

    public function Products()
    {
        return $this->belongsToMany(Product::class, "std_group");
    }
}
