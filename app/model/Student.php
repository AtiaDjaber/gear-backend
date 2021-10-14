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
    public function groups(): HasManyDeep
    {
        return $this->hasManyDeep(
            Group::class,
            [StdGroupTeacher::class, GroupTeacher::class],
            ['student_id', 'group_id', 'id'],
            ['id', 'group_teacher_id', 'id']
        );
    }
}
