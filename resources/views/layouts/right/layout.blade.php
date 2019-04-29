@extends('layouts.body')

@section('right')
    <div class="panel panel-default">
        <div class="panel-heading">
            @if($__env->yieldContent('subtitle'))
                @yield('subtitle')
            @else
                @yield('title')
            @endif
            
            @yield('buttontop')
        </div>
        <div class="panel-body">
            @yield('button')
            @yield('content')
        </div>
    </div>
@endsection
