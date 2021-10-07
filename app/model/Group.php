<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;
use LevelyearsSubjs;

class Group extends Model
{
    public function teachers()
    {
        return $this->BelongsToMany(Teacher::class);
    }
    public function levelyearsSubjs()
    {
        return $this->belongsTo(SubjLevelYear::class);
    }
    public function subjs()
    {
        return $this->hasOne(Subj::class)->using(SubjLevelYear::class);
    }
}
