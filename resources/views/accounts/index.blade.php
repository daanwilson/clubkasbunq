@extends('layouts.right.index')

@section('title','Bank accounts')

@section('buttontop')
<div class="pull-right">
    <a class="btn btn-default btn-xs" title="Sync" href="{{ route('accounts.index','refresh') }}">
        <span class="hidden-xs">Refresh data : </span><i class="fas fa-sync" aria-hidden="true"></i>
    </a>
</div>
@endsection

@section('content')
<div class="row">
    @foreach($bankaccounts as $bankaccount)
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-primary" style="border-color:{{ $bankaccount->color }}">
            <div class="panel-heading" style="border-color:{{ $bankaccount->color }};background-color:{{ $bankaccount->color }};cursor:pointer;" onclick="window.location='{{ route('account.payments.index',$bankaccount->id) }}'">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-money fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">{{ MoneyFormat($bankaccount->amount) }}</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">{{ $bankaccount->IBAN }}</div>
                    <div class="col-md-6 text-right">{{ $bankaccount->description }}</div>
                </div>
            </div>
            <a href="{{ route('account.payments.index',$bankaccount->id) }}" style="color:{{ $bankaccount->color }}">
                <div class="panel-footer">
                    <span class="pull-left">Bekijk Details</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    @endforeach
</div>
@endsection
