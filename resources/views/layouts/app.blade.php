<link href="{{ asset('css/home.css') }}" rel="stylesheet">


<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>IS351</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body class="background">
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/home') }}">
                    IS351
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <!-- <ul class="navbar-nav me-auto">
                     <li  class="nav-item">
                             <a class="nav-link" href="{{route('roles.index')}}">Manage Roles</a>
                        </li>
                        <li  class="nav-item">
                             <a class="nav-link" href="{{route('users.index')}}">Manage Users</a>
                        </li>
                    </ul> -->

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
                       
                            <li class="nav-item dropdown">
                                 <!-- <a class="nav-link dropdown-toggle" href="{{route('users.index')}}">
                                    Manage users
                                </a> -->
                                <a id="navbarDropdown" class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <img src="{{ Auth::user()->profile_photo_url }}" alt="avatar" class="rounded-circle" width="28" height="28">
                                    <span>{{ Auth::user()->name }}</span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('my.submissions') }}">My Submissions</a>
                                    <a class="dropdown-item" href="{{ route('profile') }}">Profile</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
                <aside class="col-2 border-end vh-100 p-3 aside-background">
                    <ul class="nav flex-column">
                        @auth
                            @if(auth()->user() && method_exists(auth()->user(), 'hasRole') && auth()->user()->hasRole('admin'))
                                <li class="nav-item mb-2">
                                    <a class="nav-link btn-css" href="{{ route('admin.registrations.index') }}">Admin Dashboard</a>
                                </li>
                                <li class="nav-item mb-2">
                                    <a class="nav-link btn-css" href="{{ route('roles.index') }}">Manage Roles</a>
                                </li>
                                <li class="nav-item mb-2">
                                    <a class="nav-link btn-css" href="{{ route('users.index') }}">Manage Users</a>
                                </li>
                            @endif
                            <li class="nav-item mb-2">
                                <a class="nav-link btn-css" href="{{ route('my.submissions') }}">My Submissions</a>
                            </li>
                            <li class="nav-item mb-2">
                                <a class="nav-link btn-css" href="{{ route('profile') }}">Profile</a>
                            </li>
                        @endauth
                    </ul>
                </aside>
           

        <main class="col-10 py-4">
          
            @yield('content')
        </main>
    </div>
     </div>
        </div>
</body>
</html>
