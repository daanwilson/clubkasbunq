@extends('layouts.right.layout')

@section('title','Kleine kas')

@section('content')
<div id="dashboard">
    <div class="row">
        @foreach($cashaccounts as $account)
        
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-primary" style="border-color:{{ $account->team->color }};">
                        <div class="panel-heading" style="border-color:{{ $account->team->color }};background-color:{{ $account->team->color }};cursor:pointer;" onclick="window.location='{{ route('cash.edit',$account->team->id) }}'">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-wallet fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="ellipsis">{{$account->getName()}}</div>
                                    <div class="huge">0</div>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('cash.edit',$account->team->id) }}" style="color:{{ $account->team->color }}">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
        @endforeach
            </div>
</div>
@endsection
