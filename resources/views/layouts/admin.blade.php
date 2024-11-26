<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div id="app">
        <!-- Navbar -->
        @unless(Route::is('login'))
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('programchair.dashboard') }}">
                    Dashboard
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="#">Profile</a></li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        @endunless

        <div class="d-flex">
            <!-- Sidebar -->
            <div class="bg-secondary p-3" style="width: 250px; min-height: 100vh;">
                <h4 class="text-white text-center py-3">{{ Auth::user()->role }} Panel</h4>
                <!-- Shared Dashboard Link -->
                <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('programchair.dashboard') }}"
                   class="d-block p-2 text-white {{ request()->routeIs(Auth::user()->role === 'admin' ? 'admin.dashboard' : 'programchair.dashboard') ? 'bg-dark' : '' }}">
                    Dashboard
                </a>

                <!-- Admin-Only Links -->
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.faculty.index') }}" class="d-block p-2 text-white {{ request()->routeIs('admin.faculty.index') ? 'bg-dark' : '' }}">Manage Faculty</a>
                    <a href="{{ route('admin.subjects.index') }}" class="d-block p-2 text-white {{ request()->routeIs('admin.subjects.index') ? 'bg-dark' : '' }}">Manage Subjects</a>
                    <a href="{{ route('admin.rooms.index') }}" class="d-block p-2 text-white {{ request()->routeIs('admin.rooms.index') ? 'bg-dark' : '' }}">Manage Rooms</a>
                @endif

                <!-- Manage Schedules Dropdown -->
                <div class="dropdown">
                    <a href="#" class="d-block p-2 text-white dropdown-toggle {{ request()->routeIs('admin.schedules.*', 'programchair.schedules.*') ? 'bg-dark' : '' }}" id="scheduleDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        Manage Schedules
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item {{ request()->routeIs(Auth::user()->role === 'admin' ? 'admin.schedules.index' : 'programchair.schedules.index') ? 'active' : '' }}" href="{{ Auth::user()->role === 'admin' ? route('admin.schedules.index') : route('programchair.schedules.index') }}">
                                List of Schedules
                            </a>
                        </li>
                        @if(Auth::user()->role === 'admin')
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('admin.schedules.create') ? 'active' : '' }}" href="{{ route('admin.schedules.create') }}">
                                    Add Schedule
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <div class="content p-4" style="width: 100%;">
                @yield('content')
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-light text-center py-3 mt-4">
            <p class="mb-0">&copy; {{ date('Y') }} Unibersidad De Zamboanga | School of Engineering Communication Technology. All Rights Reserved.</p>
        </footer>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
