<?php

namespace App\Http\Controllers;

use App\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $vtable = (new \App\Team())->getTableConfig();
        //dd($teamstable);
        return view('teams.index',  compact('vtable'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function data(Request $request)
    {
        //
        return \App\Team::filtered($request)->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $team = new \App\Team();
        $accounts = \Auth::User()->BankAccounts();
        return view('teams.edit',  compact('team','accounts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = array(
            'name'=>'required|unique:teams',
        );
        
        $this->validate($request,$validate);
        $team = new \App\Team();
        $team->name = $request->name;
        $team->color = $request->color;
        $team->start_age = 0;
        $team->end_age = 0;
        $team->start_season_month = 1;
        $team->save();
        //(request(['name','color']));
        //return redirect('/team/'.$team->id);
        return ['message'=>'Wijziging succesvol opgeslagen','status'=>'success','result'=>true,'redirect'=>config('app.url').'team/'.$team->id ];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function show(Team $team)
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
        $accounts = \Auth::User()->BankAccountsArray();
        return view('teams.edit',  compact('team','accounts'));
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
        $validate = array(
            'name'=>'required',
        );
        $this->validate($request,$validate);      
        $team->update(request(['name','color','bankaccount_id']));
        
        return ['message'=>'Wijziging succesvol opgeslagen','status'=>'success','result'=>true];
        //$request->session()->flash('status', 'Wijziging succesvol opgeslagen');
        //return redirect('/team/'.$team->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function destroy(Team $team)
    {
        $team->delete();
        return ['message'=>'Team is verwijderd','status'=>'danger','result'=>true ];
        //request()->session()->flash('success','Team is verwijderd.');
        //return redirect('/teams/');
    }
}
