@extends('layouts.right.edit')

@section('title','Post bewerken/toevoegen')
@section('route.back')

@section('content')
<form autocomplete="off" method="POST" action="" class="form-horizontal form-vue" @submit.prevent="onSubmit">
    {{ csrf_field() }}
    @include('layouts.form.input',['type'=>'text','data'=>$MoneyItem,'field'=>'item_name','label'=>'Naam','required'=>true,'autofocus'=>true])
     
    @include('layouts.form.buttons')
    
</form>
@endsection
