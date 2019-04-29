@extends('layouts.app')

@section('title','Betaallink succesvol afgerond')

@section('body')
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Betaling succesvol</h3>
                    </div>
                    <div class="panel-body">
                        <div class="alert alert-success">
                            Betaling succesvol afgerond.
                        </div>
                        <div>
                            <img src="/img/success-minions.gif" alt="success minions" width="500" height="226" class="img-responsive" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection