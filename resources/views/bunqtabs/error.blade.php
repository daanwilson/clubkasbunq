@extends('layouts.app')

@section('title','Betaallink error')

@section('body')
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Error</h3>
                    </div>
                    <div class="panel-body">
                        <div class="alert alert-danger">{{$error}}</div>
                        <p>U kunt dit eventueel melden aan onderstaand email adres:</p>
                        <a href="mailto:{{env('MAIL_FROM_ADDRESS')}}">{{env('MAIL_FROM_ADDRESS')}}</a>@yield("paymentrequest_body")
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection