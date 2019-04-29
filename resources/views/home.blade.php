@extends('layouts.right.layout')

@section('title','Dashboard')

@section('content')
<div id="dashboard">
    <div class="row">
        @foreach($teams as $team)
        
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-primary" style="border-color:{{ $team->color }};">
                        <div class="panel-heading" style="border-color:{{ $team->color }};background-color:{{ $team->color }};cursor:pointer;" onclick="window.location='{{ route('members.index','f=Teams:'.$team->id) }}'">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-users fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div>{{$team->name}}</div>
                                    <span class="huge">{{ $team->YouthMembers()->count() }}</span>&nbsp;<span>Leden</span>
                                    <div>{{ $team->LeaderMembers()->count(DB::raw('DISTINCT `members`.`member_id`')) }} &nbsp;Leiding</div>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('members.index','f=Teams:'.$team->id) }}" style="color:{{ $team->color }}">
                            <div class="panel-footer">
                                <span class="pull-left">Bekijk leden {{$team->name}}</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
        @endforeach
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-green">
                        <div class="panel-heading" style="cursor:pointer;" onclick="window.location='{{ route('accounts.index') }}'">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="far fa-credit-card fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right"  title="{{MoneyFormat($bankamount)}}">
                                    <div>Bankrekeningen</div>
                                    <div class="huge ellipsis">{{ MoneyFormat($bankamount) }}</div>
                                    <div>Geld op {{ $bankcount }} rekeningen</div>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('accounts.index') }}">
                            <div class="panel-footer">
                                <span class="pull-left">Bekijk bankrekeningen</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-red">
                        <div class="panel-heading" onclick="window.location='{{ route('cash.index') }}'">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-wallet fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right" title="{{MoneyFormat($cash_total)}}">
                                    <div>Kleine kas</div>
                                    <div class="huge ellipsis">{{MoneyFormat($cash_total)}}</div>
                                    <div>Geld in {{ count($cash) }} kleine kas{{(count($cash) > 1 ? 'sen' : '')}}</div>
                                </div>
                            </div>
                        </div>
                        <a href="{{route('cash.index')}}">
                            <div class="panel-footer">
                                <span class="pull-left">Bekijke kleine kas</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
</div>
@endsection
