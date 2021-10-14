<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class StdGroupTeacher extends Pivot
{
    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $table = 'std_group_teacher';
    // public $timestamps = false;

    public function groupTeacher()
    {
        return $this->belongsTo(GroupTeacher::class);
    }
    // https://plugins.zhile.io

    static public function alldata()
    {
        return
            StdGroupTeacher::Join('group_teacher', 'std_group_teacher.group_teacher_id', 'group_teacher.id')
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

    static public function getGroupSubjsByStudent($id)
    {
        return
            StdGroupTeacher::where('student_id', $id)->LeftJoin('group_teacher', 'std_group_teacher.group_teacher_id', 'group_teacher.id')
            ->Join('teachers', 'group_teacher.teacher_id', 'teachers.id')
            ->Join('groups', 'group_teacher.group_id', 'groups.id')
            ->Join('subjs', 'groups.subj_id', 'subjs.id')
            ->select(
                'teachers.*',
                'groups.id as groupId',
                'groups.name as groupName',
                'subjs.id as subjId',
                'subjs.name as subjName',
                'subjs.grade as subjGrade',
                'subjs.level as subjLevel'

            );
    }
}
