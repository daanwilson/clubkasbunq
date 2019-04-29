@extends('layouts.right.layout')

@section('buttontop')    
    <div class="pull-right">
        @yield('buttontopextra')
        <a class="btn btn-default btn-xs" title="Terug" href="javascript:window.history.back();">
            <i class="fa fa-arrow-circle-left" aria-hidden="true"></i>
        </a>
    </div>
@endsection
