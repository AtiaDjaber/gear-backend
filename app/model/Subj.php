<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Subj extends Model
{

    protected $table = "subjs";
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function groups()
    {
        return $this->hasMany(Group::class);
    }
}
