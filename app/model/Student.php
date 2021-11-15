<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;
use \Staudenmeir\EloquentHasManyDeep\HasManyDeep;

class Student extends Model
{
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    //
    protected $table = "students";
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function groupTeachers()
    {
        return $this->BelongsToMany(GroupTeacher::class);
    }
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
        return $this->hasManyThrough(Group::class, StudentGroup::class);
    }
}
