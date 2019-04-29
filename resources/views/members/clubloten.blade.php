@extends('layouts.right.edit')

@section('title','Leden importeren')
@section('route.back',route('members.index'))

@section('content')
<blockquote>
    <div>
        Importeer het clubloten bestand voor <strong>seizoen {{ $season->season_name }}</strong>. Deze is te downloaden vanaf de Grote clubactie website :
        <a href="https://clubactie.nl/Inloggen" target="_blank">clubactie.nl/Inloggen</a>. Log daar in met onderstaande gegevens:
    </div>
    <div>
        Username: 346HUB<br/>
        Wachtwoord: 346HUB<br/>
    </div>
    <div>
        Ga naar verkochte loten, en filter op het juiste jaar en selecteer alle verkochte loten en klik op Exporteren. Het geexporteerde XML bestand kan hier ingelezen worden.
    </div>
</blockquote>
<form autocomplete="off" method="POST" action="" class="form-horizontal form-vue" @submit.prevent="onSubmit" enctype="multipart/form-data">
    {{ csrf_field() }}

    @include('layouts.form.alert',['data'=>$season,'field'=>'upload_result'])
    @include('layouts.form.input',['type'=>'file','data'=>$season,'field'=>'upload_file','label'=>'Bestand','required'=>true])

    <div class="pull-right">
        <button type="submit" class="btn btn-primary" :disabled="form.errors.any()">Importeren</button>
        <a href="{{route('members.index')}}" class="btn btn-default">Terug</a>
    </div>
</form>
@endsection
