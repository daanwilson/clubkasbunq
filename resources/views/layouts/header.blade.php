<nav class="navbar navbar-default navbar-static-top" role="navigation">
    <div class="navbar-header">

        <!-- Collapsed Hamburger -->
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle Navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>

        <!-- Branding Image -->
        <a class="navbar-brand ellipsis" href="{{ url('/') }}">
            {{ config('app.name', 'Clubkas') }}
        </a>
    </div>
    <!-- Right Side Of Navbar -->
    <ul class="nav navbar-top-links navbar-right">
        <!-- Authentication Links -->
        @guest
        <li><a href="{{ route('login') }}">Login</a></li>
        @else
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                {{ Auth::user()->name }} <span class="caret"></span>
            </a>
            {!! $topMenu->topMenu()->asUl(['class'=>'dropdown-menu','role'=>'menu']) !!}

        </li>
        @endguest
    </ul>
    @auth
    <!-- Right Side Of Navbar -->
    <ul class="nav navbar-top-links navbar-right">
        <!-- Authentication Links -->
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                Seizoen {{ App\Season::current()->season_name }} <span class="caret"></span>
            </a>
            <ul class="dropdown-menu" role="menu">
                @foreach(App\Season::AllCached() as $season)
                    <li><a href="{{ $season->link() }}">{{ $season->season_name}}</a></li>
                @endforeach
            </ul>

        </li>
    </ul>
    @endauth

    <div class="navbar-default sidebar" role="navigation">
        <div class="sidebar-nav navbar-collapse">
            <ul id="side-menu" class="nav in" role="presentation">
                @include(config('laravel-menu.views.bootstrap-items'), ['items' => $sideMenu->roots()])
            </ul>
        </div>
        <!-- /.sidebar-collapse -->
    </div>
</nav>
