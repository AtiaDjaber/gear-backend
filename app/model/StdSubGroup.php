<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class StdSubGroup extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $table = 'std_group_teacher';
    // public $timestamps = false;
}
