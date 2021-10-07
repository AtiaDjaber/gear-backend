<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $table = "teachers";
    protected $guarded = ['id', 'created_at', 'updated_at'];
    //

    public function groups()
    {
        return $this->BelongsToMany(Group::class);
    }
}
