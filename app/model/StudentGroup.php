<?php

namespace App\model;

use \Staudenmeir\EloquentHasManyDeep\HasManyDeep;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductGroup extends Pivot
{
    // use \Znck\Eloquent\Traits\BelongsToThrough;

    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $table = 'std_group';
    // public $timestamps = false;

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s', // Change your format
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'date' => 'datetime:Y-m-d H:i:s',
    ];
    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function stduent()
    {
        return $this->belongsTo(Product::class, 'Product_id');
    }

    public function groupClient()
    {
        return $this->belongsTo(GroupClient::class);
    }
    // public function groups()
    // {
    //     return $this->hasManyThrough(Group::class, ProductGroup::class);
    // }



    // https://plugins.zhile.io

    static public function alldata()
    {
        return
            ProductGroup::Join('group_Client', 'std_group_Client.group_Client_id', 'group_Client.id')
            ->Join('groups', 'group_Client.group_id', 'groups.id')
            ->Join('Clients', 'group_Client.Client_id', 'Clients.id')
            ->Join('Products', 'std_group_Client.Product_id', 'Products.id')
            ->Join('subjs', 'groups.subj_id', 'subjs.id')
            ->select(
                'std_group_Client.created_at',
                'Clients.id as ClientId',
                'Clients.firstname as ClientFirstname',
                'Clients.lastname as ClientLastname',
                'Clients.mobile as ClientMobile',
                //
                'groups.id as groupId',
                'groups.name as groupName',
                //
                'subjs.id as subjId',
                'subjs.name as subjName',
                'subjs.grade as subjGrade',
                'subjs.level as subjLevel',
                //
                'Products.id as ProductId',
                'Products.firstname as ProductFirstname',
                'Products.lastname as ProductLastname',
                'Products.barcode',
                'Products.mobile'
            );
    }

    // static public function getGroupSubjsByProduct($id)
    // {
    //     return
    //         ProductGroup::where('Product_id', $id)->LeftJoin('group_Client', 'std_group_Client.group_Client_id', 'group_Client.id')
    //         // ->Join('Clients', 'group_Client.Client_id', 'Clients.id')
    //         ->Join('groups', 'group_Client.group_id', 'groups.id')
    //         ->Join('subjs', 'groups.subj_id', 'subjs.id')
    //         ->select(
    //             'groups.id as groupId',
    //             'groups.name as groupName',
    //             'subjs.id as subjId',
    //             'subjs.name as subjName',
    //             'subjs.grade as subjGrade',
    //             'subjs.level as subjLevel'
    //         );
    // }


    public function subj()
    {
        return $this->hasManyDeep(
            Subj::class,
            [Group::class],
            ['id', 'id'],
            ['id', 'subj_id']
        )->withIntermediate(Group::class);
    }
    public function Client()
    {
        return $this->hasManyDeep(
            Client::class,
            [Group::class],
            ['id', 'id'],
            ['id', 'Client_id']
        );
    }
}
