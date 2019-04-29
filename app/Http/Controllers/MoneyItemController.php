<?php

namespace App\Http\Controllers;

use App\MoneyItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MoneyItemController extends Controller
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
        $vtable = (new \App\MoneyItem())->getTableConfig();
        return view('moneyitems.index',  compact('vtable'));
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
        return \App\MoneyItem::filtered($request)->get();
        //return \DB::getQueryLog();
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $MoneyItem = new \App\MoneyItem();
        return view('moneyitems.edit',  compact('MoneyItem'));
        
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
            'item_name'=>'required',
        );
        
        $this->validate($request,$validate);
        $p = new \App\MoneyItem();
        $p->item_name = $request->item_name;        
        $p->save();
        //(request(['name','color']));
        //return redirect('/team/'.$team->id);
        return ['message'=>'Wijziging succesvol opgeslagen','status'=>'success','result'=>true,'redirect'=>route('moneyitem.show',$p->id) ];

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MoneyItem  $moneyItem
     * @return \Illuminate\Http\Response
     */
    public function show(MoneyItem $moneyItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MoneyItem  $moneyItem
     * @return \Illuminate\Http\Response
     */
    public function edit(MoneyItem $MoneyItem)
    {
        return view('moneyitem.edit',  compact('MoneyItem'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MoneyItem  $moneyItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MoneyItem $MoneyItem)
    {
        $validate = array(
            'item_name'=>'required',
        );
        $this->validate($request,$validate);      
        $MoneyItem->update(request(['item_name']));
        
        return ['message'=>'Wijziging succesvol opgeslagen','status'=>'success','result'=>true];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MoneyItem  $moneyItem
     * @return \Illuminate\Http\Response
     */
    public function destroy(MoneyItem $MoneyItem)
    {
        $MoneyItem->delete();
        return ['message'=>'Post is verwijderd','status'=>'danger','result'=>true ];
    }
}
