@extends('layouts.right.edit')
@section('title','Toestemming bewerken / toevoegen')
@section('route.back')

@section('left')
    @include('users.menu')
@endsection

@section('content')
    <form autocomplete="off" method="POST" action="" class="form-horizontal form-vue" @submit.prevent="onSubmit">
    {{ csrf_field() }}
    @include('layouts.form.input',['type'=>'text','data'=>$permission,'field'=>'name','label'=>'Naam','required'=>true,'autofocus'=>true])
    @include('layouts.form.input',['type'=>'text','data'=>$permission,'field'=>'display_name','label'=>'Weergave naam','required'=>true])
    @include('layouts.form.input',['type'=>'textarea','data'=>$permission,'field'=>'description','label'=>'Omschrijving','required'=>true])
    @include('layouts.form.buttons')
    </form>
    
@endsection
