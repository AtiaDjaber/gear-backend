<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Expense extends Model
{

    protected $guarded = ['id', 'created_at', 'updated_at'];
}
