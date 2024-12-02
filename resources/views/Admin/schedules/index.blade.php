@extends('layouts.admin')

@section('title', 'List of Schedules')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">List of Faculty</h1>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Faculty List Table -->
    <h6 class="mt-4">Faculty List</h6>
    <table class="table table-bordered">
        <thead style="background-color: maroon; color: white;">
            <tr>
                <th>Faculty Name</th>
                <th>Position</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($faculties as $faculty)
                <tr>
                    <td>{{ $faculty->first_name }} {{ $faculty->last_name }}</td>
                    <td>{{ $faculty->position }}</td>
                    <td>
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('admin.faculty.schedules', $faculty->id) }}" class="btn btn-info btn-sm">View Schedules</a>
                        @elseif(Auth::user()->role === 'program chair')
                            <a href="{{ route('programchair.faculty.schedules', $faculty->id) }}" class="btn btn-info btn-sm">View Schedules</a>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">No faculty members available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="d-flex justify-content-center mt-4">
        {{ $faculties->links('vendor.pagination.bootstrap-4') }}
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        console.log('Faculty list page loaded.');
    });
</script>
@endpush
