<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Role;
use App\Permission;
use App\Team;
use App\BankAccount;
use DB;
use App\Http\Middleware\dynamicTable;

class RoleController extends Controller
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
        $vtable = (new \App\Role())->getTableConfig();
        //dd($teamstable);
        return view('roles.index',  compact('vtable'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function data(Request $request)
    {
         return \App\Role::filtered($request)->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permission = Permission::all();
        $teams = Team::all();
        $role = new Role();
        $bankaccounts = BankAccount::all();
        $bankaccountsArr = [];
        $rolePermissionsArr = [];
        $roleTeamsArr = [];
        
        return view('roles.edit',compact('role','permission','teams','rolePermissionsArr','roleTeamsArr','bankaccounts','bankaccountsArr'));
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
            'name' => 'required|unique:roles,name',
            'display_name' => 'required',
            'description' => 'required',
            'permission' => 'required',
        ]);

        $role = new Role();
        $role->name = $request->input('name');
        $role->display_name = $request->input('display_name');
        $role->description = $request->input('description');
        $role->save();

        foreach ($request->input('permission') as $key => $value) {
            $role->attachPermission($value);
        }
        $role->Teams()->sync($request->input('teams'));
        $role->BankAccounts()->sync($request->input('bankaccounts'));
        
        
        return ['message'=>'Rol succesvol aangemaakt.','status'=>'success','result'=>true,'redirect'=>config('app.url').'role/'.$role->id];
        
        /*return redirect()->route('roles.index')
                        ->with('success','Rol succesvol aangemaakt.');*/
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        $rolePermissions = $role->hasMany('App\Permission');
        return view('roles.show',compact('role','rolePermissions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        $permission = Permission::all();
        $teams = Team::all();
        $bankaccounts = BankAccount::all();
        
        
        $rolePermissionsArr = $role->getPermissionsArray();
        $roleTeamsArr = $role->getTeamsArray();
        $bankaccountsArr = $role->getBankAccountArray();
        //dd($roleTeamsArr);
        return view('roles.edit',compact('role','permission','teams','rolePermissionsArr','roleTeamsArr','bankaccounts','bankaccountsArr'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $this->validate($request, [
            'display_name' => 'required',
            'description' => 'required',
            'permission' => 'required',
        ]);

        $role->display_name = $request->input('display_name');
        $role->description = $request->input('description');
        $role->save();
        
        //\App\PermissionRole::where("permission_role.role_id",'=',$role->id)->delete();
        //foreach ($request->input('permission') as $key => $value) {
        //    $role->attachPermission($value);
        //}
        $role->perms()->sync($request->input('permission'));
        $role->Teams()->sync($request->input('teams'));
        $role->BankAccounts()->sync($request->input('bankaccounts'));
        return ['message'=>'Rol succesvol bijgewerkt.','status'=>'success','result'=>true];
        
        /*return redirect()->route('role.edit',$role->id)
                        ->with('success','Rol succesvol bijgewerkt.');*/
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        //$role->delete();
        Role::whereId($role->id)->delete();
        return ['message'=>'Rol is verwijderd','status'=>'danger','result'=>true ];
        /*return redirect()->route('roles.index')
                        ->with('success','Rol succesvol verwijderd');*/
    }
}
