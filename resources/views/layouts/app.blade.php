<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Faculty Scheduling'))</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Custom Styles (Optional) -->
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
    <div id="app">
        @unless (Route::is('login'))
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
                <div class="container-fluid">
                    <a class="navbar-brand" href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('programchair.dashboard') }}">
                        {{ config('app.name', 'Faculty Scheduling') }}
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav ms-auto">
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
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        {{ Auth::user()->first_name ?? Auth::user()->name }} {{ Auth::user()->last_name ?? '' }}
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                        @if (Auth::user()->role === 'admin')
                                            <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Admin Dashboard</a></li>
                                        @elseif (Auth::user()->role === 'program chair')
                                            <li><a class="dropdown-item" href="{{ route('programchair.dashboard') }}">Program Chair Dashboard</a></li>
                                        @endif
                                        <li>
                                            <a class="dropdown-item" href="{{ route('logout') }}"
                                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                {{ __('Logout') }}
                                            </a>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                                @csrf
                                            </form>
                                        </li>
                                    </ul>
                                </li>
                            @endguest
                        </ul>
                    </div>
                </div>
            </nav>
        @endunless

        <div class="container-fluid">
            <div class="row">
                <!-- Sidebar -->
                @unless(Route::is('login'))
                    <div class="col-md-2 bg-secondary text-white py-4">
                        <h5 class="text-center">{{ ucfirst(Auth::user()->role) }} Panel</h5>
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link text-white {{ request()->routeIs(Auth::user()->role === 'admin' ? 'admin.dashboard' : 'programchair.dashboard') ? 'active' : '' }}" href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('programchair.dashboard') }}">
                                    Dashboard
                                </a>
                            </li>
                            @if(Auth::user()->role === 'admin')
                                <li class="nav-item"><a class="nav-link text-white" href="{{ route('admin.faculty.index') }}">Manage Faculty</a></li>
                                <li class="nav-item"><a class="nav-link text-white" href="{{ route('admin.subjects.index') }}">Manage Subjects</a></li>
                                <li class="nav-item"><a class="nav-link text-white" href="{{ route('admin.rooms.index') }}">Manage Rooms</a></li>
                            @endif
                            <li class="nav-item">
                                <a class="nav-link text-white {{ request()->routeIs(Auth::user()->role === 'admin' ? 'admin.schedules.*' : 'programchair.schedules.*') ? 'active' : '' }}" href="{{ Auth::user()->role === 'admin' ? route('admin.schedules.index') : route('programchair.schedules.index') }}">
                                    Manage Schedules
                                </a>
                            </li>
                        </ul>
                    </div>
                @endunless

                <!-- Main Content -->
                <main class="col-md-10 py-4">
                    @yield('content')
                </main>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-light text-center py-3 mt-4">
            <p class="mb-0">&copy; {{ date('Y') }} Unibersidad De Zamboanga | School of Engineering Communication Technology. All Rights Reserved.</p>
        </footer>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
