@extends('layouts.right.edit')

@section('title','Leden importeren')
@section('route.back',route('members.index'))

@section('content')
<blockquote>
    Importeer een leden bestand voor <strong>seizoen {{ $season->season_name }}</strong>. Deze is te downloaden vanuit scouts online : 
    <a href="https://sol.scouting.nl" target="_blank">sol.scouting.nl</a> onder het kopje 'Organisatie' - 'Leden'.<br/>
    LET OP: Zorg dat bij de export de optie 'Zo vaak als een lid functies heeft' geselecteerd is en klik vervolgens op 'Zoeken'. 
    Vervolgens kies je voor 'exporteren' waarmee een XML document wordt gedownload. Upload dit document hier.    
</blockquote>
<form autocomplete="off" method="POST" action="" class="form-horizontal form-vue" @submit.prevent="onSubmit" enctype="multipart/form-data">
    {{ csrf_field() }}
    @include('layouts.form.input',['type'=>'checkbox','data'=>$member,'field'=>'members_not_present','label'=>'Niet aanwezig','info'=>'Beindig leden die niet aanwezig zijn in dit bestand.'])
    @include('layouts.form.input',['type'=>'file','data'=>$member,'field'=>'upload_file','label'=>'Bestand','required'=>true])
   
    <div class="pull-right">
        <button type="submit" class="btn btn-primary" :disabled="form.errors.any()">Importeren</button>
        <a href="{{route('members.index')}}" class="btn btn-default">Terug</a>
    </div>
</form>
@endsection
