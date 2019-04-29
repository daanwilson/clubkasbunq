<?php

namespace App\Http\Controllers;

use App\Cash;
use App\Team;
use Illuminate\Http\Request;

class CashController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cashaccounts = auth()->user()->CashAccounts();
        return view('cash.home',compact('cashaccounts'));
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function data(Request $request,Team $team)
    {

        $cash = new Cash();
        $cash->setTeam($team);
        $q = $cash->newQuery()->where('team_id', '=', $team->id);

        return Cash::appendQuery($q, $request, [])->paginate(500);
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
        $cash= new Cash();
        $request->amount = (double)$request->amount;
        $save = ['team_id','date','amount','description','season_id','purpose_id','item_id'];
        foreach($save as $s){
            $cash->$s = $request->$s;
        }
        $cash->save();
        //$data = ['team_id'=>$request->team_id,'date'=>$request->date,'season_id'=>$request->season_id];
        $data = $request->except(['amount','description']);
        return ['message'=>'Regel succesvol toegevoegd.','status'=>'success','result'=>true,'data'=>$data,'reload_tables'=>true,'hide_modal'=>true];

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Cash  $cash
     * @return \Illuminate\Http\Response
     */
    public function show(Cash $cash)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function edit(Team $team)
    {
        $vtable = (new \App\Cash())->setTeam($team)->getTableConfig();
        $money_purposes = \App\MoneyPurpose::all();
        $money_items = \App\MoneyItem::all();
        $seasons = \App\Season::all();
        $currentseason_id = \App\Season::current()->id;
        return view('cash.edit',  compact('vtable','money_purposes','money_items','seasons','currentseason_id','team'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Team $team)
    {
        try{
            if($request->id>0){
                $cash = Cash::find($request->id);
                /*$validate = array(
                    'desciption'=>'required',
                );*/
                //$this->validate($request,$validate);
                //$cash->update(request(['season_id']));
                $request->amount = (double)$request->amount;
                $save = ['amount','description','season_id','purpose_id','item_id'];
                foreach($save as $s){
                    $cash->$s = $request->$s;
                }
                $cash->save();

                return ['message'=>'Wijziging succesvol opgeslagen','status'=>'success','result'=>true];
            }
        }catch (\Exception $e){
            return ['message'=>$e->getMessage(),'status'=>'danger','result'=>false];
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cash  $cash
     * @return \Illuminate\Http\Response
     */
    public function destroy(Team $team,Cash $cash)
    {
        if($team->id!=$cash->team_id){
            return ['message'=>'Er is iets fout gegaan bij het verwijderen','status'=>'danger','result'=>false];
        }
        $cash->delete();
        return ['message'=>'Regel is verwijderd','status'=>'success','result'=>true];
    }
}
