<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;
use LevelyearsSubjs;

class Group extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, "teacher_id");
    }
    public function subj()
    {
        return $this->belongsTo(Subj::class, "subj_id");
    }
}
