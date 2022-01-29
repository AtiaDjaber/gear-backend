<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;
use \Staudenmeir\EloquentHasManyDeep\HasManyDeep;

class Client extends Model
{
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s', // Change your format
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];
    // protected $hidden=["pivot"];
    public function group()
    {
        return $this->BelongsToMany(Group::class)
            ->using(GroupClient::class)->withTimestamps();
    }
    public function subjs()
    {
        return $this->hasManyDeep(
            Subj::class,
            [GroupClient::class, Group::class],
            ['Client_id', 'id', 'id'],
            ['id',  'group_id', 'subj_id']
        )->withIntermediate(Group::class);
    }

    public function Products()
    {
        return $this->hasManyDeep(
            Product::class,
            [GroupClient::class, StdGroupClient::class],
            ['Client_id', 'group_Client_id', 'id'],
            ['id',  'id', 'Product_id']
        );
    }
}
