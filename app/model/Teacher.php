<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;
use \Staudenmeir\EloquentHasManyDeep\HasManyDeep;

class Teacher extends Model
{
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    protected $table = "teachers";
    protected $guarded = ['id', 'created_at', 'updated_at'];
    //
    // protected $hidden=["pivot"];
    public function groups()
    {
        return $this->BelongsToMany(Group::class)
            ->using(GroupTeacher::class)->withTimestamps();
    }
    public function subjs()
    {
        return $this->hasManyDeep(
            Subj::class,
            [GroupTeacher::class, Group::class],
            ['teacher_id', 'id', 'id'],
            ['id',  'group_id', 'subj_id']
        )->withIntermediate(Group::class);
    }

    public function students()
    {
        return $this->hasManyDeep(
            Student::class,
            [GroupTeacher::class, StdGroupTeacher::class],
            ['teacher_id', 'group_teacher_id', 'id'],
            ['id',  'id', 'student_id']
        );
    }
}
