<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class MoneyItem extends Model
{
    use ModelCustom;
    
    function getTableConfig(){
        return array(
            'src'=>route('moneyitem.data'),
            'primary_key'=>'id',
            'view'=>false,
            'edit'=>true,
            'edit_url'=>'/money/items',
            'remove'=>true,            
            'remove_url'=>'/money/items',
            
            'search'=>array(
                'id'=>array('operator'=>'=','value'=>'%s'),
                'item_name'=>array('operator'=>'like','value'=>'%%%s%%'),
            ),
            
        );
    }

    protected static function boot()
    {
        parent::boot();

        // Order by name ASC
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('item_name', 'asc');
        });
    }
}
