@extends('layouts.right.edit')

@section('title','Bankrekening '.strtolower($bankAccount->description))
@section('subtitle',$bankAccount->IBAN)
@section('route.back')


@section('buttontopextra')
<a class="btn btn-default btn-xs" title="Sync data" href="{{ route('account.'.$tab.'.index',['id'=>$bankAccount->id,'refresh']) }}">
    <span class="hidden-xs">Refresh data : </span><i class="fas fa-sync" aria-hidden="true"></i>
</a>&nbsp;
@endsection

@section('content')
<button class="btn {{ $bankAccount->amount>0 ? 'btn-success' : 'btn-danger' }} pull-right" type="button">
    Balans&nbsp;<span class="badge">{{ $bankAccount->getAmountFormated() }}</span>
</button>


<!-- Nav tabs -->
<?php /*  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="{{ ($tab=='payments'?'active':'') }}"><a href="{{ route('account.payments.index',['id'=>$bankAccount->id]) }}" role="tab">Betalingen</a></li>
    <li role="presentation" class="{{ ($tab=='requests'?'active':'') }}"><a href="{{ route('account.requests.index',['id'=>$bankAccount->id]) }}" role="tab">Betaalverzoeken</a></li>
  </ul>
<br/>
 */ ?>
  <div class="tab-content">
      <div role="tabpanel" class="tab-pane active">
          @if($tab=='requests')
            @include('accounts.payments.requests')
          @else
            @include('accounts.payments.payments')
          @endif
      </div>
  </div>
@endsection
