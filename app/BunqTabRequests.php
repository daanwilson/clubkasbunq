<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BunqTabRequests extends Model
{
    use ModelCustom;

    protected $table = 'bunq_tabs';
    static $accounts;

    function getTableConfig(){
        return array(
            'src'=>route('bunqtabs.data'),
            'primary_key'=>'id',
            'view'=>false,
            'edit'=>true,
            'edit_url'=>'/bunqtab',
            'remove'=>true,
            'remove_url'=>'/bunqtab',

            'search'=>array(
                /*'id'=>array('operator'=>'=','value'=>'%s'),
                'name'=>array('operator'=>'like','value'=>'%%%s%%'),
                'display_name'=>array('operator'=>'like','value'=>'%%%s%%'),
                'description'=>array('operator'=>'like','value'=>'%%%s%%'),*/
            ),

        );
    }

    function Account(){
        $accounts = BankAccount::allCachedByKey('id');
        if(array_key_exists($this->AccountId,$accounts)){
            return $accounts[$this->AccountId];
        }
        return new BankAccount();
    }
    function getUrl(){
        return env('APP_URL').'/pay/'.$this->shortname;
    }
    function getReturnUrl(){
        return env('APP_URL').'/payed/';
    }
    function isExpired(){
        $expires = new \DateTime($this->expires);
        $now = new \DateTime(date('Y-m-d'));
        if($expires<$now){
            return true;
        }
        return false;
    }
}
