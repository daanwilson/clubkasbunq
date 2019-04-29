@extends('paymentrequests.index')

@section('title','Betaallink error')

@section('paymentrequest_body')
    <div class="alert alert-danger">{{$error}}</div>
    <p>U kunt dit eventueel melden aan onderstaand email adres:</p>
    <a href="mailto:{{env('MAIL_FROM_ADDRESS')}}">{{env('MAIL_FROM_ADDRESS')}}</a>
@endsection
