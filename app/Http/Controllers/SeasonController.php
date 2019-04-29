<?php

namespace App\Http\Controllers;

use App\Season;
use Illuminate\Http\Request;

class SeasonController extends Controller
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
        $vtable = (new \App\Season())->getTableConfig();
        //dd($teamstable);
        return view('season.index',  compact('vtable'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function data(Request $request)
    {
        //
        return \App\Season::filtered($request)->get();
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $season = new \App\Season();
        return view('season.edit',  compact('season'));
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
            'season_name'=>'required|unique:seasons',
            'season_start'=>'required',
            'season_stop'=>'required',
        );
        
        $this->validate($request,$validate);
        $season = new \App\Season();
        $season->season_name = $request->season_name;
        $season->season_start = $request->season_start;
        $season->season_stop = $request->season_stop;
        $season->save();
        //(request(['name','color']));
        //return redirect('/team/'.$team->id);
        return ['message'=>'Wijziging succesvol opgeslagen','status'=>'success','result'=>true,'redirect'=>config('app.url').'season/'.$season->id ];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Season  $season
     * @return \Illuminate\Http\Response
     */
    public function show(Season $season)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Season  $season
     * @return \Illuminate\Http\Response
     */
    public function edit(Season $season)
    {
        return view('season.edit',  compact('season'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Season  $season
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Season $season)
    {
        $validate = array(
            'season_name'=>'required',
            'season_start'=>'required',
            'season_stop'=>'required',
        );
        $this->validate($request,$validate);      
        $season->update(request(['season_name','season_start','season_stop']));
        
        return ['message'=>'Wijziging succesvol opgeslagen','status'=>'success','result'=>true];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Season  $season
     * @return \Illuminate\Http\Response
     */
    public function destroy(Season $season)
    {
        $season->delete();
        return ['message'=>'Seizoen is verwijderd','status'=>'danger','result'=>true ];
    }
}
