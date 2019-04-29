<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//use Http\Middleware\tableModel;
use App\ModelCustom;

class Country extends Model
{
    use ModelCustom;
    
    protected $table = 'countries';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'color',
    ];
    
}
