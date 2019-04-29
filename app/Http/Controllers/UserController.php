<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Http\Middleware\dynamicTable;

class UserController extends Controller
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
        //$users = \App\User::all();
        //return view('users.index',  compact('users'));
        //
        $vtable = (new \App\User())->getTableConfig();
        return view('users.index',  compact('vtable'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function data(Request $request)
    {
        //
        return \App\User::filtered($request)->get();
    }
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = \App\Role::all(); 
        $userrolesArr = array();
        $user = new \App\User();
        return view('users.edit',  compact('user','roles','userrolesArr'));
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
            'name'=>'required',
            'email'=> ['email',Rule::unique('users')],
            'role'=>'required',
            'password'=>'min:6|confirmed'
        );      
        $this->validate($request,$validate);
                
        $user = new \App\User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();        
        $user->roles()->sync($request->role);
        
        return ['message'=>'Gebruiker succesvol opgeslagen','status'=>'success','result'=>true,'redirect'=>config('app.url').'user/'.$user->id ];
        //$request->session()->flash('status', 'Gebruikers succesvol opgeslagen');
        //return redirect('/user/'.$user->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(\App\User $user)
    {
        $roles = \App\Role::all(); 
        $userroles = $user->roles()->get();
        $userrolesArr = array();
        foreach($userroles as $r){
            $userrolesArr[$r->id] = $r->id;
        }
        return view('users.edit',  compact('user','roles','userrolesArr'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, \App\User $user)
    {
        $validate = array(
            'name'=>'required',
            'email'=> ['email',Rule::unique('users')->ignore($user->id, 'id')],
            'role'=>'required'
        );      
        if(request('password')!=''){
            $validate['password'] = 'min:6|confirmed';
        }
        $this->validate($request,$validate);
        
        if(request('password')!=''){
            $request->merge(['password' => Hash::make($request->password)]);
        }
        unset($validate['role']);
        $update = array_keys($validate);        
        $user->update(request($update));        
        $user->roles()->sync($request->role);
        
        return ['message'=>'Wijziging succesvol opgeslagen','status'=>'success','result'=>true];
        //$request->session()->flash('status', 'Wijziging succesvol opgeslagen');
        //return redirect('/user/'.$user->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        if(\Auth::user()->id==$id){
            //request()->session()->flash('error','Deze gebruiker kan niet verwijderd worden omdat u daarmee bent ingelogd.');
            return ['message'=>'Deze gebruiker kan niet verwijderd worden omdat u daarmee bent ingelogd.','result'=>false,'status'=>'danger' ];
        }else{
            \App\User::find($id)->delete();
            //request()->session()->flash('success','De gebruiker is verwijderd.');
            return ['message'=>'De gebruiker is verwijderd.','result'=>true,'status'=>'danger' ];
        }
        //return redirect('/users/');
        
    }
}
