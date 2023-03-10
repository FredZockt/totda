<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- SEO -->
    <title>Traders of the Dark Ages {{ Constants::META_SEPARATOR }} @yield('title')</title>
    <meta name="description" content="@yield('description')">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    <!-- Add jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Add Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app" >

        <header>
            <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
                <div class="container">
                    <a class="navbar-brand" href="{{ url('/') }}">
                        TDA
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <!-- Left Side Of Navbar -->
                        <ul class="navbar-nav me-auto">

                        </ul>

                        <!-- Right Side Of Navbar -->
                        <ul class="navbar-nav ms-auto">
                            <!-- Authentication Links -->
                            @guest
                                @if (Route::has('login'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                    </li>
                                @endif

                                @if (Route::has('register'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                    </li>
                                @endif

                            @else
                                <li class="nav-item d-lg-none">
                                    <a class="nav-link" href="/city">City</a>
                                </li>
                                <li class="nav-item d-lg-none">
                                    <a class="nav-link" href="/market">Market</a>
                                </li>
                                <li class="nav-item d-lg-none">
                                    <a class="nav-link" href="/inventory">Inventory</a>
                                </li>
                                <li class="nav-item d-lg-none">
                                    <a class="nav-link" href="/map">Map</a>
                                </li>
                                <li class="nav-item d-lg-none">
                                    <a class="nav-link" href="/work">Work</a>
                                </li>
                                <li class="nav-item d-lg-none">
                                    <a class="nav-link" href="/kingdom">Kingdom</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/settings">Settings</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/highscore">Highscore</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/search">Search</a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        {{ Auth::user()->name }}
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                            <a class="dropdown-item" href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                            document.getElementById('logout-form').submit();">
                                                {{ __('Logout') }}
                                            </a>

                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                                @csrf
                                            </form>
                                        </div>
                                    </div>
                                </li>
                            @endguest
                        </ul>
                    </div>
                </div>
            </nav>
        </header>
        <div class="app-wrapper flyout__wrapper">
            <div class="container h-100">
                <div class="row h-100">
                    <div class="col-12 col-lg-8">
                        @auth()
                        <section class="mt-4 d-none d-lg-block card ingame-menu">
                            <div class="row">
                                <div class="col-2">
                                    <a href="/city" class="{{ request()->is('city*') ? 'active' : '' }}">
                                        <img src="{{ asset('assets/images/ui/city.png')  }}" />
                                        <span>City</span>
                                    </a>
                                </div>
                                <div class="col-2">
                                    <a href="/market" class="{{ request()->is('market*') ? 'active' : '' }}">
                                        <img src="{{ asset('assets/images/ui/market.png')  }}" />
                                        <span>Market</span>
                                    </a>
                                </div>
                                <div class="col-2">
                                    <a href="/inventory" class="{{ request()->is('inventory*') ? 'active' : '' }}">
                                        <img src="{{ asset('assets/images/ui/inventory.png')  }}" />
                                        <span>Inventory</span>
                                    </a>
                                </div>
                                <div class="col-2">
                                    <a href="/map" class="{{ request()->is('map*') ? 'active' : '' }}">
                                        <img src="{{ asset('assets/images/ui/map.png')  }}" />
                                        <span>Map</span>
                                    </a>
                                </div>
                                <div class="col-2" class="{{ request()->is('work*') ? 'active' : '' }}">
                                    <a href="/work">
                                        <img src="{{ asset('assets/images/ui/work.png')  }}" />
                                        <span>Work</span>
                                    </a>
                                </div>
                                <div class="col-2">
                                    <a href="/kingdom" class="{{ request()->is('kingdom*') ? 'active' : '' }}">
                                        <img src="{{ asset('assets/images/ui/kingdom.png')  }}" />
                                        <span>Kingdom</span>
                                    </a>
                                </div>
                            </div>
                        </section>
                        @endauth
                        <main class="py-4">
                            @yield('content')
                        </main>
                    </div>

                    <div class="col-8 col-sm-6 col-md-4 flyout">
                        <div class="flyout__wrapper__btn d-lg-none">
                            <button class="toggle"></button>
                        </div>
                        <div class="flyout__wrapper__aside py-4">
                            <aside class="">
                                @if(session()->get('sidebar_city_headline') && session()->get('sidebar_city_content'))
                                    <div class="card mb-4">
                                        <div class="card-header">{{ session()->get('sidebar_city_headline') }}</div>
                                        <div class="card-body">
                                            <p class="mb-0">{{ session()->get('sidebar_city_content') }}</p>
                                        </div>
                                    </div>
                                @endif
                                @if(session()->get('sidebar_gold_headline') && session()->get('sidebar_gold_content'))
                                    <div class="card mb-4">
                                        <div class="card-header">{{ session()->get('sidebar_gold_headline') }}</div>
                                        <div class="card-body">
                                            <p class="mb-0">{{ session()->get('sidebar_gold_content') }}</p>
                                        </div>
                                    </div>
                                @endif
                                @if(session()->get('sidebar_inventory_headline') && session()->get('sidebar_inventory_content'))
                                    <div class="card mb-4">
                                        <div class="card-header">{{ session()->get('sidebar_inventory_headline') }}</div>
                                        <div class="card-body">
                                            <p class="mb-0">{{ session()->get('sidebar_inventory_content') }}</p>
                                        </div>
                                    </div>
                                @endif
                                @if(session()->get('active_job_headline') && session()->get('active_job_description'))
                                    <div class="card mb-4">
                                        <div class="card-header">{{ session()->get('active_job_headline') }}</div>
                                        <div class="card-body">
                                            <p class="mb-0">{{ session()->get('active_job_description') }}</p>
                                        </div>
                                    </div>
                                @endif
                                @if(session()->get('sidebar_gold_headline') && session()->get('sidebar_gold_content'))
                                    <img class="header-image mb-5" src="{{asset('assets/images/bg.png')}}" alt="Traveler">
                                @endif


                            </aside>
                        </div>
            
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-white shadow-sm">
        <div class="container d-flex justify-content-between my-3">
            <p>
                Tomaten
            </p>
            <p>Ehrliches Geld für ehrliche Arbeit</p>
            <p>
                &copy; By FightsGames
            </p>
        </div>
    </footer>
</body>
</html>
