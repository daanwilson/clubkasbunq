@extends('layouts.app')

@section('title','Dashboard')

@section('body')
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">{{$title}}</h3>
                    </div>
                    <div class="panel-body">
                        @yield("paymentrequest_body")
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection