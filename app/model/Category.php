<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public $timestamps = false;

    protected $table = "category";
    protected $fillable = ["id", "name"];
}
