<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];
}
