<?php

namespace App;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
//use Http\Middleware\tableModel;

class Team extends Model
{
    use ModelCustom;
    protected $table ='teams';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'color','bankaccount_id'
    ];

    function Members(){
        return $this->belongsToMany('\App\Member', 'member_teams','team_id','member_id');
    }
    function YouthMembers(){
        return \DB::table('members')
            ->join('member_teams', 'members.id', '=', 'member_teams.member_id')
            ->join('member_member_roles', 'members.id', '=', 'member_member_roles.member_id')->where('member_member_roles.team_id',$this->id)
            ->join('member_seasons', 'members.id', '=', 'member_seasons.member_id')
            ->select('members.*')
            ->where('member_seasons.season_id', Season::current()->id)
            ->where('member_member_roles.role_id',Setting('jeugdlid_functie_id'))
            ->where('member_teams.team_id',$this->id);
            
    }
    function LeaderMembers(){
        return \DB::table('members')
            ->join('member_teams', 'members.id', '=', 'member_teams.member_id')
            ->join('member_member_roles','members.id', '=', 'member_member_roles.member_id')
            ->join('member_seasons', 'members.id', '=', 'member_seasons.member_id')
            ->select('DISTINCT members.*')
            ->where('member_seasons.season_id', Season::current()->id)
            ->where('member_member_roles.role_id','!=',Setting('jeugdlid_functie_id'))
            //->where('member_member_roles.team_id',$this->id)
            ->where('member_teams.team_id',$this->id);

    }
    function getTableConfig(){
        return array(
            'src'=>route('teams.data'),
            'primary_key'=>'id',
            'view'=>false,
            'edit'=>true,
            'edit_url'=>'/team',
            'remove'=>true,            
            'remove_url'=>'/team',
            
            'search'=>array(
                'id'=>array('operator'=>'=','value'=>'%s'),
                'name'=>array('operator'=>'like','value'=>'%%%s%%'),
                'color'=>array('operator'=>'like','value'=>'%%%s%%'),
            ),
            
        );
    }
       
}
