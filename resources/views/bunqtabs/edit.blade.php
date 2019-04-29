@extends('layouts.right.edit')
@section('title','Bunq betaalverzoek bewerken / toevoegen')
@section('route.back')

@section('content')
    <form autocomplete="off" method="POST" action="" class="form-horizontal form-vue" @submit.prevent="onSubmit">
    <div class="row">
        <div class="col-md-6">

                {{ csrf_field() }}
                @include('layouts.form.input',['type'=>'select','data'=>$tab,'field'=>'AccountId','label'=>'Bankrekening','required'=>true,'values'=>$accounts,'values_key'=>'id','values_label'=>'description'])
                @include('layouts.form.input',['type'=>'text','data'=>$tab,'field'=>'amount','label'=>'Bedrag','required'=>true,'autofocus'=>true])
                @include('layouts.form.input',['type'=>'text','data'=>$tab,'field'=>'description','label'=>'Omschrijving'])
                <div class="row">
                    <label class="col-md-4">&nbsp;</label>
                    <div class="col-md-8"><small>Korte naam voor de betalings url. Bijvoorbeeld 'lenteactie'</small></div>
                </div>
                @include('layouts.form.input',['type'=>'text','data'=>$tab,'field'=>'shortname','label'=>'Korte naam'])
                @include('layouts.form.input',['type'=>'date','data'=>$tab,'field'=>'expires','label'=>'Verloopt op'])
                @include('layouts.form.buttons')

        </div>
        <div class="col md-6">
            <div>
                <img v-bind:src="'{{ env('APP_URL') }}/bunqtab/{{$tab->id}}/qrcode/'+form.data.shortname+'?'+form.data.updated_at" alt="" />
            </div>

            <a v-bind:href="'{{ env('APP_URL') }}/pay/'+ form.data.shortname" target="_blank" v-if="form.data.shortname" v-text="'{{ env('APP_URL') }}/pay/'+form.data.shortname"></a>

        </div>
    </div>
    </form>
    
@endsection
