<?php

namespace App\Http\Controllers;

use App\MoneyPurpose;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MoneyPurposeController extends Controller
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
        $vtable = (new \App\MoneyPurpose())->getTableConfig();
        return view('moneypurpose.index',  compact('vtable'));
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function data(Request $request)
    {
        //\DB::connection()->enableQueryLog();
        //return 
        return \App\MoneyPurpose::filtered($request)->get();
        //return \DB::getQueryLog();
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $MoneyPurpose = new \App\MoneyPurpose();
        return view('moneypurpose.edit',  compact('MoneyPurpose'));
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
            'purpose_name'=>'required',
        );
        
        $this->validate($request,$validate);
        $p = new \App\MoneyPurpose();
        $p->purpose_name = $request->purpose_name;        
        $p->save();
        //(request(['name','color']));
        //return redirect('/team/'.$team->id);
        return ['message'=>'Wijziging succesvol opgeslagen','status'=>'success','result'=>true,'redirect'=>route('moneypurpose.show',$p->id) ];

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MoneyPurpose  $moneyPurpose
     * @return \Illuminate\Http\Response
     */
    public function show(MoneyPurpose $moneyPurpose)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MoneyPurpose  $moneyPurpose
     * @return \Illuminate\Http\Response
     */
    public function edit(MoneyPurpose $MoneyPurpose)
    {
        return view('moneypurpose.edit',  compact('MoneyPurpose'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MoneyPurpose  $moneyPurpose
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MoneyPurpose $moneyPurpose)
    {
        $validate = array(
            'purpose_name'=>'required',
        );
        $this->validate($request,$validate);      
        $moneyPurpose->update(request(['purpose_name']));
        
        return ['message'=>'Wijziging succesvol opgeslagen','status'=>'success','result'=>true];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MoneyPurpose  $moneyPurpose
     * @return \Illuminate\Http\Response
     */
    public function destroy(MoneyPurpose $MoneyPurpose)
    {
        //
        $MoneyPurpose->delete();
        return ['message'=>'Doel is verwijderd','status'=>'danger','result'=>true ];
    }
}
