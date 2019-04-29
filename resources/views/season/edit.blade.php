@extends('layouts.right.edit')

@section('title','Seizoen bewerken/toevoegen')
@section('route.back')

@section('content')
<form autocomplete="off" method="POST" action="" class="form-horizontal form-vue" @submit.prevent="onSubmit">
    {{ csrf_field() }}
    @include('layouts.form.input',['type'=>'text','data'=>$season,'field'=>'season_name','label'=>'Naam','required'=>true,'autofocus'=>true])
    @include('layouts.form.input',['type'=>'date','data'=>$season,'field'=>'season_start','label'=>'Start','required'=>true])
    @include('layouts.form.input',['type'=>'date','data'=>$season,'field'=>'season_end','label'=>'Stop','required'=>true])
    
    @include('layouts.form.buttons')
    
</form>
@endsection
