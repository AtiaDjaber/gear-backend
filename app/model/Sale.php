<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s', // Change your format
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'date' => 'datetime:Y-m-d H:i:s',
    ];

    public function subjs()
    {
        return $this->BelongsToMany(Subj::class);
    }
}
