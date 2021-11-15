<?php

namespace App\model;

use \Staudenmeir\EloquentHasManyDeep\HasManyDeep;

use Illuminate\Database\Eloquent\Relations\Pivot;

class StudentGroup extends Pivot
{
    // use \Znck\Eloquent\Traits\BelongsToThrough;

    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $table = 'std_group';
    // public $timestamps = false;

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function stduent()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function groupTeacher()
    {
        return $this->belongsTo(GroupTeacher::class);
    }
    // public function groups()
    // {
    //     return $this->hasManyThrough(Group::class, StudentGroup::class);
    // }



    // https://plugins.zhile.io

    static public function alldata()
    {
        return
            StudentGroup::Join('group_teacher', 'std_group_teacher.group_teacher_id', 'group_teacher.id')
            ->Join('groups', 'group_teacher.group_id', 'groups.id')
            ->Join('teachers', 'group_teacher.teacher_id', 'teachers.id')
            ->Join('students', 'std_group_teacher.student_id', 'students.id')
            ->Join('subjs', 'groups.subj_id', 'subjs.id')
            ->select(
                'std_group_teacher.created_at',
                'teachers.id as teacherId',
                'teachers.firstname as teacherFirstname',
                'teachers.lastname as teacherLastname',
                'teachers.mobile as teacherMobile',
                //
                'groups.id as groupId',
                'groups.name as groupName',
                //
                'subjs.id as subjId',
                'subjs.name as subjName',
                'subjs.grade as subjGrade',
                'subjs.level as subjLevel',
                //
                'students.id as studentId',
                'students.firstname as studentFirstname',
                'students.lastname as studentLastname',
                'students.barcode',
                'students.mobile'
            );
    }

    // static public function getGroupSubjsByStudent($id)
    // {
    //     return
    //         StudentGroup::where('student_id', $id)->LeftJoin('group_teacher', 'std_group_teacher.group_teacher_id', 'group_teacher.id')
    //         // ->Join('teachers', 'group_teacher.teacher_id', 'teachers.id')
    //         ->Join('groups', 'group_teacher.group_id', 'groups.id')
    //         ->Join('subjs', 'groups.subj_id', 'subjs.id')
    //         ->select(
    //             'groups.id as groupId',
    //             'groups.name as groupName',
    //             'subjs.id as subjId',
    //             'subjs.name as subjName',
    //             'subjs.grade as subjGrade',
    //             'subjs.level as subjLevel'
    //         );
    // }


    public function subj()
    {
        return $this->hasManyDeep(
            Subj::class,
            [Group::class],
            ['id', 'id'],
            ['id', 'subj_id']
        )->withIntermediate(Group::class);
    }
    public function teacher()
    {
        return $this->hasManyDeep(
            teacher::class,
            [Group::class],
            ['id', 'id'],
            ['id', 'teacher_id']
        );
    }
}
