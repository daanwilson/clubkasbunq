@extends('layouts.app')
@section('title','Login')
@section('body')
    <div class="container">
		
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">{{ config('app.name') }}</h3>
                    </div>
                    <div class="panel-body">
			@include('layouts.status')
                        <form role="form" action="{{ route('login') }}" method="post">
                            {{ csrf_field() }}
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="E-mail" name="email" type="email" autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Password" name="password" type="password" value="">
                                    <p class="help-block text-right"><a href="password/reset">Wachtwoord vergeten?</a></p>
                                </div>
                                <!-- Change this to a button or input when using this as a form -->
                                <button type="submit" class="btn btn-lg btn-success btn-block">Login</button>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection