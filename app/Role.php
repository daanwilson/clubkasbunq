<?php
namespace App;

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
    use ModelCustom;
    
    function getPermissions(){
        return $this->hasMany('App\Permission');
    }
    function getPermissionsArray(){
        $rolePermissions  = $this->hasMany('App\PermissionRole')->get();
        $rolePermissionsArr = [];
        foreach($rolePermissions as $rp){
            $rolePermissionsArr[] = $rp->permission_id;
        }
        return $rolePermissionsArr;
    }
    function getTeamsArray(){
        $roleTeams  = $this->hasMany('App\TeamRole')->get();
        $roleTeamsArr = [];
        foreach($roleTeams as $rp){
            $roleTeamsArr[] = $rp->team_id;
        }
        return $roleTeamsArr;
    }
    function getBankAccountArray(){
        $array  = $this->BankAccounts()->get();
        $arr = [];
        foreach($array as $a){
            $arr[] = $a->id;
        }
        return $arr;
    }
    function getAttributesIncRoles() {
        $data = parent::getAttributes();        
        $data['permission'] = $this->getPermissionsArray();
        $data['team'] = $this->getTeamsArray();
        return $data;
    }
    /**
     * Many-to-Many relations with the permission model.
     * Named "Teams" for backwards compatibility.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function Teams()
    {
        return $this->belongsToMany('\App\Team', 'teams_role','role_id','team_id');
    }
    /**
     * Many-to-Many relations with the permission model.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function Roles()
    {
        return $this->hasMany('App\PermissionRole');
    }
    /**
     * Many-to-Many relations with the permission model.
     * Named "Teams" for backwards compatibility.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function BankAccounts()
    {
        return $this->belongsToMany('\App\BankAccount', 'bank_account_roles','role_id','bankaccount_id');
    }
    function getTableConfig(){
        return array(
            'src'=>route('roles.data'),
            'primary_key'=>'id',
            'view'=>false,
            'edit'=>true,
            'edit_url'=>'/role',
            'remove'=>true,            
            'remove_url'=>'/role',
            
            'search'=>array(
                'id'=>array('operator'=>'=','value'=>'%s'),
                'name'=>array('operator'=>'like','value'=>'%%%s%%'),
                'display_name'=>array('operator'=>'like','value'=>'%%%s%%'),
                'description'=>array('operator'=>'like','value'=>'%%%s%%'),
            ),
            
        );
    }
}
