<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Levelyear extends Model
{

    protected $casts = [
        'created_at' => 'datetime:Y-d-m H:i:s', // Change your format
        'updated_at' => 'datetime:Y-d-m H:i:s',
        'date' => 'datetime:Y-d-m H:i:s',
    ];

    public function subjs()
    {
        return $this->BelongsToMany(Subj::class);
    }
}
