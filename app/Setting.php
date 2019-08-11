<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends  Model
{
    use ModelCustom;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'key', 'value', 'info'
    ];
    
    function getTableConfig(){
        return array(
            'src'=>route('settings.data'),
            'primary_key'=>'id',
            'view'=>false,
            'edit'=>true,
            'edit_url'=>'/setting',
            'remove'=>true,            
            'remove_url'=>'/setting',
            
            'search'=>array(
                /*'id'=>array('operator'=>'=','value'=>'%s'),
                'name'=>array('operator'=>'like','value'=>'%%%s%%'),
                'display_name'=>array('operator'=>'like','value'=>'%%%s%%'),
                'description'=>array('operator'=>'like','value'=>'%%%s%%'),*/
            ),
            
        );
    }
}
