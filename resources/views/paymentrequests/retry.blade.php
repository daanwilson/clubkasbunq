@extends('paymentrequests.index')

@section('title','Betaallink error')

@section('paymentrequest_body')
    <div class="alert alert-danger">Betaling mislukt.</div>
    <p>
        <a href="{{$link}}" class="btn btn-primary">Probeer het opnieuw</a>

    </p>
@endsection
