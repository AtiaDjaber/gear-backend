<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;
use LevelyearsSubjs;

class Group extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'created_at' => 'datetime:Y-d-m H:i:s', // Change your format
        'updated_at' => 'datetime:Y-d-m H:i:s',
        'date' => 'datetime:Y-d-m H:i:s',
    ];
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
