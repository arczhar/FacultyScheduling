<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">

    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.5/index.global.min.css" rel="stylesheet" />

    <!-- jQuery (Load First) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Check for jQuery -->
    <script>
        if (typeof $ === 'undefined') {
            console.error('jQuery ($) is not defined. Ensure it is loaded properly.');
        }
    </script>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        /* Custom pagination and sidebar styles */
        .pagination svg {
            width: 16px;
            height: 16px;
        }
        .pagination .page-item {
            margin: 0 5px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('programchair.dashboard') }}">
            Dashboard
        </a>

        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{ Auth::user()->name }}
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="#">Profile</a>
                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Logout
                    </a>
                </div>
            </li>
        </ul>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </nav>

    <!-- Sidebar -->
    <div class="d-flex">
        <div class="bg-secondary p-3" style="width: 250px; min-height: 100vh;">
            <h4 class="text-white text-center py-3">{{ Auth::user()->role }} Panel</h4>

            <!-- Dashboard -->
            <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('programchair.dashboard') }}"
               class="d-block p-2 text-white {{ request()->routeIs(Auth::user()->role === 'admin' ? 'admin.dashboard' : 'programchair.dashboard') ? 'bg-dark' : '' }}">
                Dashboard
            </a>

            <!-- Admin-Only Links -->
            @if(Auth::user()->role === 'admin')
                <a href="{{ route('admin.faculty.index') }}" class="d-block p-2 text-white {{ request()->routeIs('admin.faculty.index') ? 'bg-dark' : '' }}">Manage Faculty</a>
                <a href="{{ route('admin.subjects.index') }}" class="d-block p-2 text-white {{ request()->routeIs('admin.subjects.index') ? 'bg-dark' : '' }}">Manage Subjects</a>
                <a href="{{ route('admin.rooms.index') }}" class="d-block p-2 text-white {{ request()->routeIs('admin.rooms.index') ? 'bg-dark' : '' }}">Manage Rooms</a>
                <a href="{{ route('admin.examrm.examroom') }}" class="d-block p-2 text-white {{ request()->routeIs('admin.examrm.examroom') ? 'bg-dark' : '' }}">Manage Exam Rooms</a>
            @endif

            <!-- Manage Schedules (Shared) -->
            <div class="dropdown">
                <a href="#" class="d-block p-2 text-white dropdown-toggle {{ request()->routeIs('admin.schedules.*', 'programchair.schedules.*') ? 'bg-dark' : '' }}" id="scheduleDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Manage Schedules
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item {{ request()->routeIs(Auth::user()->role === 'admin' ? 'admin.schedules.index' : 'programchair.schedules.index') ? 'active' : '' }}" href="{{ Auth::user()->role === 'admin' ? route('admin.schedules.index') : route('programchair.schedules.index') }}">
                        List of Schedules
                    </a>
                    <a class="dropdown-item {{ request()->routeIs(Auth::user()->role === 'admin' ? 'admin.schedules.create' : 'programchair.schedules.create') ? 'active' : '' }}" href="{{ Auth::user()->role === 'admin' ? route('admin.schedules.create') : route('programchair.schedules.create') }}">
                        Add Schedule
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="content p-4" style="width: 100%;">
            @yield('content')
        </div>
    </div>

    <!-- Popper.js (Load Second) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>

    <!-- Bootstrap JS (Load Third) -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    <!-- FullCalendar Global JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.5/index.global.min.js"></script>

    <!-- Check for jQuery Conflicts -->
    <script>
        if (typeof $ === 'undefined') {
            console.error('jQuery ($) is not defined after loading all scripts. Check for library conflicts.');
        }
    </script>

    @stack('scripts')
</body>
</html>
