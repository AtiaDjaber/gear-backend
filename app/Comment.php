<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['text', 'star'];

    public function article()
    {
        return $this->belongsTo('App\Article');
    }
}
