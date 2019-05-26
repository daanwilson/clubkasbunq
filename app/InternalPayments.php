<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InternalPayments extends Model
{
    use ModelCustom;

    //
    static function getCurrent()
    {
        $current = static::where('season_id',Season::current()->id)->get();
        $updates = [];
        foreach($current as $c){
            $updates[$c->payment_type] = $c;
        }
        return $updates;
    }
}
