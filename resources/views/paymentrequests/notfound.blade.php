@extends('layouts.right.layout')

@section('title','Dashboard')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Betaallink niet gevonden</h3>
                    </div>
                    <div class="panel-body">
                        <div class="alert alert-danger">Sorry, de betaallink is niet gevonden. Excuses voor het ongemak.</div>
                        <br/>U kunt dit melden via onderstaand email adres:<br/>
                        <a href="mailto:{{env('MAIL_FROM_ADDRESS')}}">{{env('MAIL_FROM_ADDRESS')}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
