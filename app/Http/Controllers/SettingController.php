<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Setting;
use App\Permission;
use DB;
use App\Http\Middleware\dynamicTable;

class SettingController extends Controller
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
    public function index(Request $request)
    {
        $vtable = (new \App\Setting())->getTableConfig();
        //dd($teamstable);
        return view('settings.index',  compact('vtable'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function data(Request $request)
    {
         return \App\Setting::filtered($request)->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        /*$permission = Permission::get();
        
        $rolePermissionsArr = array();
        return view('setting.edit',compact('role','permission','rolePermissionsArr'));*/
        $setting = new Setting();
        return view('settings.edit',compact('setting'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $this->validate($request, [
            'key' => 'required|unique:settings,key',
            'value' => 'required',
        ]);
        $setting = new Setting();
        $setting->key = $request->input('key');
        $setting->value = $request->input('value');
        $setting->info = $request->input('info');
        $setting->save();
        return ['message'=>'Instelling succesvol aangemaakt.','status'=>'success','result'=>true,'redirect'=>config('app.url').'setting/'.$setting->id];
        
        /*return redirect()->route('roles.index')
                        ->with('success','Rol succesvol aangemaakt.');*/
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Setting $setting)
    {
        /*$rolePermissions = $role->hasMany('App\Permission');*/
        return view('settings.show',compact('setting'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Setting $setting)
    {
        return view('settings.edit',compact('setting'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Setting $setting)
    {
        $this->validate($request, [
            'key' => 'required|unique:settings,key,'.$setting->id,
            'value' => 'required',
        ]);

        $setting->key = $request->input('key');
        $setting->value = $request->input('value');
        $setting->info = $request->input('info');
        $setting->save();
        return ['message'=>'Instelling succesvol bijgewerkt.','status'=>'success','result'=>true];

        /*return redirect()->route('role.edit',$role->id)
                        ->with('success','Rol succesvol bijgewerkt.');*/
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Setting $setting)
    {
        $setting->delete();
        return ['message'=>'Instelling  is verwijderd','status'=>'danger','result'=>true ];
        /*return redirect()->route('roles.index')
                        ->with('success','Rol succesvol verwijderd');*/
    }
}
