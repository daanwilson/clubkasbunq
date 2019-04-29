@extends('layouts.right.edit')

@section('title','Lid bekijken')
@section('route.back',route('members.index'))

@section('content')
    <form autocomplete="off" method="POST" action="" class="form-horizontal form-vue" @submit.prevent="onSubmit">
        {{ csrf_field() }}
        @include('layouts.form.input',['type'=>'hidden','data'=>$member,'field'=>'id','label'=>''])

        <div class="row">
            <div class="col-sm-6">
                <div><strong>Lidnummer</strong></div>
                {{ $member->member_id }}
            </div>
            <div class="col-sm-6">
                <div><strong>Naam</strong></div>
                {{ $member->fullname() }}
            </div>
        </div>
        <hr/>
        <div class="row">
            <div class="col-sm-6">
                <div><strong>Persoonsgegevens</strong></div>
                ({{ $member->member_initials }}) {{ $member->fullname() }}
                @if($member->member_gender=='m')
                    (Man)
                @endif
                @if($member->member_gender=='v')
                    (Vrouw)
                @endif
                <div>{{ $member->member_phone }}</div>
                <div>{{ $member->member_mobile }}</div>
                <a href="mailto:{{ $member->member_email }}" title="Email">{{ $member->member_email }}</a>
            </div>
            <div class="col-sm-6">
                <div><strong>Adresgegevens</strong></div>
                {{ $member->member_street }} {{ $member->member_number.$member->member_number_addition }}<br/>
                {{ $member->member_zipcode }} {{ $member->member_place }}<br/>
                {{ $member->Country()->first()->country_name }}
            </div>
        </div>
        <hr/>
        <div class="row">
            <div class="col-sm-6">
                <div><strong>Geboortedatum</strong></div>
                {{ $member->member_birthdate->format('d-m-Y') }} ({{ $member->BirthCountry()->first()->country_name }}
                )<br/>
                {{ $member->member_birthdate->age }} jaar.
            </div>
            <div class="col-sm-6">
                <div><strong>Oudergegevens</strong></div>
                <div>{{$member->member_parent1_name}}</div>
                <div>{{$member->member_parent1_phone}}</div>
                <div><a href="mailto:{{ $member->member_parent1_email }}"
                        title="Email">{{$member->member_parent1_email}}</a></div>
                <div>{{$member->member_parent2_name}}</div>
                <div>{{$member->member_parent2_phone}}</div>
                <div><a href="mailto:{{ $member->member_parent2_email }}"
                        title="Email">{{$member->member_parent2_email}}</a></div>

            </div>
        </div>
        <hr/>
        <div class="row">
            <div class="col-sm-6">
                <div><strong>Speltakken</strong></div>
                @foreach($member->Teams as $team)
                    <div>{{ $team->name }}</div>
                @endforeach
            </div>
            <div class="col-sm-6">
                <div><strong>Functies</strong></div>
                @foreach($member->MemberRoles as $role)
                    <div>{{ $role->role_name }}</div>
                @endforeach
            </div>
        </div>
        <hr/>
        <div class="row">
            <div class="col-sm-6">
                <div><strong>Aantal verkochten Clubloten</strong></div>
                @include('layouts.form.text',['type'=>'number','min'=>0,'data'=>$member->SeasonData(),'field'=>'clubloten_count','label'=>'','vue'=>true ])
                @include('layouts.form.checkbox',['type'=>'checkbox','data'=>$member->SeasonData(),'field'=>'clubloten_paid','label'=>'','info'=>'Afbetaald','vue'=>true ])
            </div>
            <div class="col-sm-6">

            </div>
        </div>
        @include('layouts.form.buttons')
    </form>
@endsection
