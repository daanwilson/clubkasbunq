@extends('layouts.right.edit')

@section('title','Team bewerken/toevoegen')
@section('route.back')

@section('content')
<form autocomplete="off" method="POST" action="" class="form-horizontal form-vue" @submit.prevent="onSubmit">
    {{ csrf_field() }}
    @include('layouts.form.input',['type'=>'text','data'=>$team,'field'=>'name','label'=>'Naam','required'=>false,'autofocus'=>true])
    @include('layouts.form.input',['type'=>'color','data'=>$team,'field'=>'color','label'=>'Kleur','required'=>true])
    @include('layouts.form.input',['type'=>'select','data'=>$team,'field'=>'bankaccount_id','label'=>'Bank account','values'=>$accounts])

    @include('layouts.form.buttons')
    
</form>
@endsection
