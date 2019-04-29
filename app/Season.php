<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class Season extends Model
{
    use ModelCustom;
    
    function getTableConfig(){
        return array(
            'src'=>route('season.data'),
            'primary_key'=>'id',
            'view'=>false,
            'edit'=>true,
            'edit_url'=>route('season.show',null),
            'remove'=>true,            
            'remove_url'=>route('season.delete',null),
            
            'search'=>array(
                'id'=>array('operator'=>'=','value'=>'%s'),
                'season_name'=>array('operator'=>'like','value'=>'%%%s%%'),
            ),
            
        );
    }
    static function current(){
        //Session::forget('season');
        if(request('season')>0){
            static::setSeason(request('season'));
        }
        $id = Session::get('season', function(){
            $seasons = static::allCached();
            $now = new \DateTime('now');
            foreach($seasons as $season){
                $start = new \DateTime($season->season_start);
                $end = new \DateTime($season->season_end);
                if($now>=$start && $now<=$end){
                    return $season->id;
                }
            }
        });
        //dd(static::find($id));
        if($id>0){
            return static::find($id);
        }
    }
    static function setSeason($id){
        Session::forget('season');
        Session::put('season',(int)$id);
        //return static::current();
    }
    function link(){
        $url = url()->current();
        $get = $_GET;
        unset($get['season']);
        $get['season']=$this->id;
        $query = http_build_query($get);
        $url.='?'.$query;
        return $url;
        
    }
}
