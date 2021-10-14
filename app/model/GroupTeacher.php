<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use LevelyearsSubjs;

class GroupTeacher extends Pivot
{
    protected $table = 'group_teacher';
    // protected $fillable = ['group_id', 'teacher_id'];
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function students()
    {
        return $this->BelongsToMany(Student::class);
    }


    static public function getStudentsByTeacher($id)
    {
        return
            GroupTeacher::where('teacher_id', $id)->LeftJoin('std_group_teacher', 'group_teacher.id', 'std_group_teacher.group_teacher_id')
            ->Join('students', 'std_group_teacher.student_id', 'students.id')
            ->select(
                'students.*'
            );
    }
    static public function getGroupSubjsByTeacher($id)
    {
        return
            GroupTeacher::where('teacher_id', $id)->LeftJoin('groups', 'group_teacher.group_id', 'groups.id')
            ->Join('subjs', 'groups.subj_id', 'subjs.id')
            ->select(
                'group_teacher.created_at',
                'groups.id as groupId',
                'groups.name as groupName',
                'subjs.id as subjId',
                'subjs.name as subjName',
                'subjs.grade as subjGrade',
                'subjs.level as subjLevel'

            );
    }

    //    public $timestamps = false;
}
