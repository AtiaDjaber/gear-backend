<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    //
    protected $table = "students";
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function groupTeachers()
    {
        return $this->BelongsToMany(GroupTeacher::class);
    }
}
