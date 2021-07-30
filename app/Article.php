<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'title', 'slug', 'body',
    ];

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    public function set_best_comment($comment)
    {
        $this->best_comment_id = $comment->id;
        return $this->save();
    }
}
