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

    public function sessions()
    {
        return $this->hasManyDeep(
            Session::class,
            [StudentGroup::class, Group::class],
            ['student_id', 'group_id', 'id', 'group_id',],
            ['id', 'id', 'group_id', "id"]
        )->withIntermediate(Group::class);
    }


    public function groups()
    {
        return $this->belongsToMany(Group::class, "std_group");
    }

    public function studentGroups()
    {
        return $this->hasMany(StudentGroup::class);
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
