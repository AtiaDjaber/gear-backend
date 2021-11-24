<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;
use \Staudenmeir\EloquentHasManyDeep\HasManyDeep;

class Student extends Model
{
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    //
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s', // Change your format
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'date' => 'datetime:Y-m-d H:i:s',
    ];

    public function subjs()
    {
        return $this->hasManyDeep(
            Subj::class,
            [StdGroupTeacher::class, GroupTeacher::class, Group::class],
            ['student_id', 'id', 'id', 'id'],
            ['id', 'group_teacher_id', 'group_id', 'subj_id']
        )->withIntermediate(Group::class);
    }
    public function groups()
    {
        return $this->belongsToMany(Group::class, "std_group");
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    // public function groups()
    // {
    //     return $this->hasManyThrough(Group::class, StudentGroup::class);
    // }
}
