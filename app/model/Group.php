<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;
use LevelyearsSubjs;

class Group extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function teachers()
    {
        return $this->BelongsToMany(Teacher::class);
    }
    public function subjs()
    {
        return $this->belongsTo(Subj::class,"subj_id");
    }

}
