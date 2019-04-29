<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Role;
use App\Permission;
use DB;
use App\Http\Middleware\dynamicTable;

class PermissionController extends Controller
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
        $vtable = (new \App\Permission())->getTableConfig();
        //dd($teamstable);
        return view('permissions.index',  compact('vtable'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function data(Request $request)
    {
 
        return Permission::filtered($request)->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permission = Permission::get();
        return view('permissions.edit',compact('permission'));
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
            'name' => 'required|unique:permissions,name',
            'display_name' => 'required',
            'description' => 'required',
        ]);

        $permission = new Permission();
        $permission->name = $request->input('name');
        $permission->display_name = $request->input('display_name');
        $permission->description = $request->input('description');
        $permission->save();

        return ['message'=>'Toestemming succesvol aangemaakt.','status'=>'success','result'=>true,'redirect'=>config('app.url').'permission/'.$permission->id];
        
        /*return redirect()->route('roles.index')
                        ->with('success','Rol succesvol aangemaakt.');*/
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Permission $permission)
    {
        return view('permissions.show',compact('permission'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Permission $permission)
    {
        return view('permissions.edit',compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Permission $permission)
    {
        $this->validate($request, [
            'display_name' => 'required',
            'description' => 'required',
        ]);

        $permission->display_name = $request->input('display_name');
        $permission->description = $request->input('description');
        $permission->save();
        
        return ['message'=>'Toestemming succesvol bijgewerkt.','status'=>'success','result'=>true];
        
        /*return redirect()->route('role.edit',$role->id)
                        ->with('success','Rol succesvol bijgewerkt.');*/
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $permission)
    {
        //$role->delete();
        $permission->delete();
        return ['message'=>'Toestemming is verwijderd','status'=>'danger','result'=>true ];
        /*return redirect()->route('roles.index')
                        ->with('success','Rol succesvol verwijderd');*/
    }
}
