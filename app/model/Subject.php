<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $table = "subjects";
    protected $guarded = ['id', 'created_at', 'updated_at'];
}
