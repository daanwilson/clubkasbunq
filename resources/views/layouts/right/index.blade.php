@extends('layouts.right.layout')

@section('button')
    @if($__env->yieldContent('label.new'))
        <div class="pull-right">
            <a href="@yield('route.new')" class="btn btn-success"><i class="fa fa-plus"></i>&nbsp;@yield('label.new')</a>
        </div>
    @endif
@endsection
