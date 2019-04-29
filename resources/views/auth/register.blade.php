@extends('layouts.app')
@section('title','Registreren')
@section('body')

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Registreer gebruiker voor {{ config('app.name') }}</h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" method="POST" action="{{ route('register') }}">
            {{ csrf_field() }}

            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                <label for="name" class="col-md-4 control-label">Naam</label>

                <div class="col-md-6">
                    <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                    @if ($errors->has('name'))
                    <span class="text-danger input-error-icon" title="{{ $errors->first('name') }}"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i></span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                <label for="email" class="col-md-4 control-label">E-Mail adres</label>

                <div class="col-md-6">
                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                    @if ($errors->has('email'))
                    <span class="text-danger input-error-icon" title="{{ $errors->first('email') }}"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i></span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                <label for="password" class="col-md-4 control-label">Wachtwoord</label>

                <div class="col-md-6">
                    <input id="password" type="password" class="form-control" name="password" required>

                    @if ($errors->has('password'))
                    <span class="text-danger input-error-icon" title="{{ $errors->first('password') }}"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i></span>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <label for="password-confirm" class="col-md-4 control-label">Bevestig wachtwoord</label>

                <div class="col-md-6">
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-6 col-md-offset-4">
                    <button type="submit" class="btn btn-primary">
                        Registreer
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
