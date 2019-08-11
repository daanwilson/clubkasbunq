<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPassword as ResetPasswordNotification;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable
{
    use Notifiable;
    use EntrustUserTrait;
    use ModelCustom;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    public function sendPasswordResetNotification($token)
    {
        // Your your own implementation.
        $this->notify(new ResetPasswordNotification($token));
    }

    function getTableConfig(){
        return array(
            'src'=>route('users.data'),
            'primary_key'=>'id',
            'view'=>false,
            'edit'=>true,
            'edit_url'=>'/user',
            'remove'=>true,            
            'remove_url'=>'/user',
            
            'search'=>array(
                'id'=>array('operator'=>'=','value'=>'%s'),
                'name'=>array('operator'=>'like','value'=>'%%%s%%'),
                'email'=>array('operator'=>'like','value'=>'%%%s%%'),
            ),
        );
    }
    function BankAccounts(){
        $accounts = array();
        $roles = $this->roles()->get();
        foreach($roles as $role){
            foreach($role->BankAccounts()->get() as $account){
                $accounts[] = $account;
            }           
        }
        return $accounts;
    }
    function BankAccountsArray($result=[0=>' - Select - ']){
        $accounts = $this->BankAccounts();
        foreach ($accounts as $account){
            $result[$account->id] = $account->description .' ('.$account->IBAN.')';
        }
        return $result;
    }
    function CashAccounts(){
        $teams = $this->Teams();
        $accounts = [];
        foreach($teams as $team){
            $accounts[] = (new Cash())->setTeam($team);
        }
        return $accounts;
    }
    function TeamIds(){
        $teams = $this->Teams();
        $ids = [];
        foreach($teams as $team){
            $ids[] = $team->id;
        }
        return $ids;
        
    }
    function Teams($with_account=false){
        $teams = array();
        $roles = $this->roles()->get();
        foreach($roles as $role){
            foreach($role->Teams()->get() as $team){
                if(!$with_account || $team->bankaccount_id>0){
                    $teams[] = $team;
                }
            }           
        }
        return $teams;
    }
}
