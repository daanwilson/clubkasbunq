@extends('layouts.app')
@section('body')
    @auth
        @include('layouts.header')
        <div id='page-wrapper'>
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header hidden-xs">@yield('title')</h1>
                    @yield('right')
                </div>
            </div>
        </div>
    @endauth
@endsection