@extends('layouts.right.edit')

@section('title','Gebruiker bewerken')
@section('route.back')

@section('left')
    @include('users.menu')
@endsection

@section('content')
<form autocomplete="off" class="form-horizontal form-vue" method="POST" action="{{($user->id>0 ? route('user.edit',$user->id) : route('user.store'))}}" @submit.prevent="onSubmit">
    {{ csrf_field() }}
    @include('layouts.form.input',['type'=>'text','data'=>$user,'field'=>'name','label'=>'Naam','required'=>true,'autofocus'=>true])
    @include('layouts.form.input',['type'=>'email','data'=>$user,'field'=>'email','label'=>'Email adres','required'=>true])
    @include('layouts.form.input',['type'=>'checkboxes','data'=>$user,'field'=>'role','label'=>'Rollen','required'=>true,'values'=>$roles,'values_key'=>'id','values_label'=>'name','current'=>$userrolesArr])
    @if($user->id>0)
        <div class="help-block">Voer alleen uw wachtwoord in als u deze wilt aanpassen.</div>
    @endif
    @include('layouts.form.input',['type'=>'password','data'=>$user,'field'=>'password','label'=>'Wachtwoord','required'=>($user->id==0)])
    @include('layouts.form.input',['type'=>'password','data'=>$user,'field'=>'password_confirmation','label'=>'Herhaal wachtwoord','required'=>($user->id==0)])
    
    @include('layouts.form.buttons')
</form>
@endsection
