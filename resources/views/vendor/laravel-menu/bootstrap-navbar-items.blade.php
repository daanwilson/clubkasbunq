@foreach($items as $item)
<li@lm-attrs($item) @if($item->hasChildren())class ="dropdown"@endif @lm-endattrs>
    @if($item->link) <a@lm-attrs($item->link) @if($item->hasChildren()) class="dropdown-toggle" data-toggle="dropdown" @endif @lm-endattrs href="{!! $item->url() !!}">
                    {!! $item->title !!}
                    {!! $item->label !!}
                    @if($item->hasChildren()) <div class="fa arrow"></div> @endif
    </a>
    @else
    {!! $item->title !!}
    @endif
    @if($item->hasChildren())
    <ul class="nav nav-second-level collapse" aria-expanded="false">
        @include(config('laravel-menu.views.bootstrap-items'), 
        array('items' => $item->children()))
    </ul> 
    @endif
</li>
@if($item->divider)
<li{!! Lavary\Menu\Builder::attributes($item->divider) !!}></li>
@endif
@endforeach
