<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;
use LevelyearsSubjs;

class GroupTeacher extends Model
{
    protected $table = 'group_teacher';
    // protected $fillable = ['group_id', 'teacher_id'];
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function students()
    {
        return $this->BelongsToMany(Student::class);
    }


    public $timestamps = false;
}
