<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

class SubjLevelYear extends Pivot
{
    protected $table = "levelyear_subj";
    protected $hidden = ['id'];
    protected $fillable = ['levelyear_id', 'subj_id'];
    public $timestamps = false;
}
