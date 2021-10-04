<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Levelyear extends Model
{

    public function subjs()
    {
        return $this->BelongsToMany(Subj::class);
    }
}
