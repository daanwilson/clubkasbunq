@extends('layouts.right.edit')
@section('title','Instelling bewerken / toevoegen')
@section('route.back')

@section('content')
    <form autocomplete="off" method="POST" action="" class="form-horizontal form-vue" @submit.prevent="onSubmit">
    {{ csrf_field() }}
    @include('layouts.form.input',['type'=>'text','data'=>$setting,'field'=>'key','label'=>'Instelling','required'=>true,'autofocus'=>true])
    @include('layouts.form.input',['type'=>'text','data'=>$setting,'field'=>'value','label'=>'Waarde','required'=>true])
    @include('layouts.form.input',['type'=>'textarea','data'=>$setting,'field'=>'info','label'=>'Info'])

    @include('layouts.form.buttons')
    </form>
    
@endsection
