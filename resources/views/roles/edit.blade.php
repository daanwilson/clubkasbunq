@extends('layouts.right.edit')
@section('title','Rol bewerken / toevoegen')
@section('route.back')

@section('left')
    @include('users.menu')
@endsection

@section('content')
    <form autocomplete="off" method="POST" action="" class="form-horizontal form-vue" @submit.prevent="onSubmit">
    {{ csrf_field() }}
    @include('layouts.form.input',['type'=>'text','data'=>$role,'field'=>'name','label'=>'Naam','required'=>true,'autofocus'=>true])
    @include('layouts.form.input',['type'=>'text','data'=>$role,'field'=>'display_name','label'=>'Weergave naam','required'=>true])
    @include('layouts.form.input',['type'=>'textarea','data'=>$role,'field'=>'description','label'=>'Omschrijving','required'=>true])
    @include('layouts.form.input',['type'=>'checkboxes','data'=>$role,'field'=>'permission','label'=>'Permission','values'=>$permission,'values_key'=>'id','values_label'=>'display_name','current'=>$rolePermissionsArr])
    
    @include('layouts.form.input',['type'=>'checkboxes','data'=>$role,'field'=>'teams','label'=>'Teams','values'=>$teams,'values_key'=>'id','values_label'=>'name','current'=>$roleTeamsArr])
    
    @include('layouts.form.input',['type'=>'checkboxes','data'=>$bankaccounts,'field'=>'bankaccounts','label'=>'Bankrekeningen','values'=>$bankaccounts,'values_key'=>'id','values_label'=>'description','current'=>$bankaccountsArr])

    @include('layouts.form.buttons')
    </form>
    
@endsection
