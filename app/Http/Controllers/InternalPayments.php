<?php

namespace App\Http\Controllers;

use App\Member;
use App\Season;
use App\Team;
use App\User;
use Illuminate\Http\Request;

class InternalPayments extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teams = auth()->user()->Teams(true);

        $amounts = \App\InternalPayments::getCurrent();
        return view('internalpayments.index',compact('teams','amounts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(is_array($request->get('amounts'))){
            $updates = \App\InternalPayments::getCurrent();
            foreach($request->get('amounts') as $type=>$amount){
                if(MakeAmount($amount)>0){
                    if(array_key_exists($type,$updates)){
                        $p = $updates[$type];
                    }else{
                        $p = new \App\InternalPayments();
                    }
                    $p->season_id = Season::current()->id;
                    $p->payment_type = $type;
                    $p->payment_amount = MakeAmount($amount);
                    $p->save();
                }
            }
        }
        return $this->index();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
