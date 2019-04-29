<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cash extends Model
{
    use ModelCustom;

    public $team;
    protected $table = 'cash_payments';

    function getTableConfig(){
        $team_id = ($this->team!==null ? $this->team->id : 0 );
        return [
            'src'=>route('cash.data',$team_id),
            'primary_key'=>'id',
            'view'=>false,
            'edit'=>false,
            'edit_url'=>route('cash.data',$team_id),
            'add_url'=>route('cash.data',$team_id),
            'remove'=>true,
            'remove_url'=>route('cash.delete',[$team_id,null]),

            'search'=>[
                'id'=>['operator'=>'=','value'=>'%s'],
                'description'=>['operator'=>'like','value'=>'%%%s%%'],
            ],
            'order'=>'date|desc',
            'filter'=>['season_id'=>[null,0],'team_id'=>$team_id],

        ];
    }
    function setTeam(Team $team){
        $this->team = $team;
        return $this;
    }

    function getName(){
        return 'Kleine kas '.$this->team->name;
    }
    function getAmount(){
        return $this->where('team_id','=',$this->team->id)->sum('amount');
    }
    function getAmountFormated(){
        return MoneyFormat($this->getAmount());
    }
}
