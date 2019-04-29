<?php
namespace App;
use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{
    use ModelCustom;
    
    function getTableConfig(){
        return array(
            'src'=>route('permissions.data'),
            'primary_key'=>'id',
            'view'=>false,
            'edit'=>true,
            'edit_url'=>route('permission.edit',null),
            'remove'=>true,            
            'remove_url'=>route('permission.delete',null),
            
            'search'=>array(
                'id'=>array('operator'=>'=','value'=>'%s'),
                'name'=>array('operator'=>'like','value'=>'%%%s%%'),
                'display_name'=>array('operator'=>'like','value'=>'%%%s%%'),
                'description'=>array('operator'=>'like','value'=>'%%%s%%'),
            ),
            
        );
    }
}