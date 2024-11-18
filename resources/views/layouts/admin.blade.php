<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>

    <!-- Bootstrap CSS (CDN) -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

    <!-- Custom CSS for Admin -->
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="{{ route('admin.dashboard') }}">Admin Dashboard</a>
        
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Admin
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="adminDropdown">
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

    <!-- Sidebar and Content Layout -->
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="bg-secondary p-3" style="width: 250px; min-height: 100vh;">
            <h4 class="text-white text-center py-3">Admin Panel</h4>
            <a href="{{ route('admin.dashboard') }}" class="d-block p-2 text-white">Dashboard</a>
            <a href="{{ route('admin.faculty.index') }}" class="d-block p-2 text-white">Manage Faculty</a>
            <a href="{{ route('admin.subjects.index') }}" class="d-block p-2 text-white">Manage Subjects</a>
            <a href="{{ route('admin.rooms.index') }}" class="d-block p-2 text-white">Manage Rooms</a>
            <a href="{{ route('admin.schedules.index') }}" class="d-block p-2 text-white">Manage Schedules</a>
        </div>

        <!-- Main Content Area -->
        <div class="content p-4" style="width: 100%;">
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS and dependencies (CDN) -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
