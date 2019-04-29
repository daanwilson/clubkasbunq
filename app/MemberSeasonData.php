<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MemberSeasonData extends Model
{
    use ModelCustom;
    protected $table = 'member_season_data';
    public $timestamps = false;

    function Member(){
        return $this->belongsTo('\App\Member');
    }
}
