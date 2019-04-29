<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MemberRole extends Model
{
    use ModelCustom;
    protected $table = 'member_roles';
    
    function Members(){
        return $this->belongsToMany('\App\Member', 'member_roles','role_id','member_id');
    }
}
