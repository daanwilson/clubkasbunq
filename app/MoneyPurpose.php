<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class MoneyPurpose extends Model
{
    use ModelCustom;

    function getTableConfig(){
        return array(
            'src'=>route('moneypurpose.data'),
            'primary_key'=>'id',
            'edit'=>false,
            'view'=>true,
            'edit_url'=>route('moneypurpose.show',null),
            'remove'=>true,            
            'remove_url'=>route('moneypurpose.delete',null),
            
            'search'=>array(
                'id'=>array('operator'=>'=','value'=>'%s'),
                'purpose_name'=>array('operator'=>'like','value'=>'%%%s%%'),
            ),
            
        );
    }

    protected static function boot()
    {
        parent::boot();

        // Order by name ASC
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('purpose_name', 'asc');
        });
    }
}
