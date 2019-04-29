<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/test',function(){
    /*$bunq = new \App\Bunq();
    $tab = \bunq\Model\Generated\Endpoint\BunqMeTab::get(1866,1742);
    dd($tab);
    $request = \App\Bunq::get()->makeRequest(1742, [
        'amount' => 0.1,
        'currency' => 'EUR',
        'description' => "TEST",
        'redirectUrl'=>'https://clubkas.daanwilson.nl/bedankt',
        //'recipient' => $member->member_email
        //'recipient' => 'betaalverzoeken@hescohonk.nl',
        //'recipient' => 'maryellen.bowie@bunq.nl'
    ]);
    dd($request);*/
    //\App\Bunq::get()->createCashRegister(951907,"TEST!");
    //$list = \App\Bunq::get()->getCashRegisters(951907);
    //dd($list);
    return "ok";
});
