@extends('layouts.right.layout')

@section('title','Dashboard')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Betaallink status</h3>
                    </div>
                    <div class="panel-body">
                        <div class="">
						Er is een betaalverzoek verstuurd naar uw bankrekening. 
						Waarschijnlijk is dit een Bunq bankrekening waardoor u de betaling kunt afhandelen in de BUNQ APP op uw telefoon.
						<hr/>
						<div>Omschrijving: {{$request->getValue()->getDescription()}}</div>
						<div>Bedrag: {!! $request->getValue()->getAmountInquired()->getCurrency().'&nbsp;'.$request->getValue()->getAmountInquired()->getValue() !!}</div>
						<br/>
						Betaling status: {{$token->status}}.

						<div class="well">
						<strong>van:</strong><br/> 
						{{$request->getValue()->getUserAliasCreated()->getDisplayName()}}<br/>
						{{$bankaccount->description }} - {{$bankaccount->IBAN }}
						
						</div>
						<div class="well">
						<strong>aan:</strong><br/> 
						{{$request->getValue()->getCounterpartyAlias()->getDisplayName()}}<br/>
						{{$request->getValue()->getCounterpartyAlias()->getIban()}}
						
						</div>
						
						
						</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
