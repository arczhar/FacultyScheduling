@extends('layouts.admin')

@section('title', 'Manage Faculty')

@section('content')
<div class="container mt-4">
    <!-- Display success message if faculty added successfully -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Display validation errors -->
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Add Faculty Button -->
    <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#addFacultyModal">
        Add Faculty
    </button>

    <!-- Faculty List Table -->
    <table class="table table-hover table-striped">
        <thead class="thead-dark">
            <tr>
                <th>ID Number</th>
                <th>Name</th>
                <th>Position</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($faculties as $faculty)
                <tr>
                    <td>{{ $faculty->id_number }}</td>
                    <td>{{ $faculty->first_name }} {{ $faculty->middle_initial }} {{ $faculty->last_name }}</td>
                    <td>{{ $faculty->position }}</td>
                    <td>
                        <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editFacultyModal-{{ $faculty->id }}">
                            Edit
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Add Faculty Modal -->
    <div class="modal fade" id="addFacultyModal" tabindex="-1" role="dialog" aria-labelledby="addFacultyModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addFacultyModalLabel">Add Faculty</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.faculty.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="id_number">ID Number</label>
                            <input type="text" class="form-control" name="id_number" id="id_number" required>
                        </div>
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input type="text" class="form-control" name="first_name" id="first_name" required>
                        </div>
                        <div class="form-group">
                            <label for="middle_initial">Middle Initial</label>
                            <input type="text" class="form-control" name="middle_initial" id="middle_initial">
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name</label>
                            <input type="text" class="form-control" name="last_name" id="last_name" required>
                        </div>
                        <div class="form-group">
                            <label for="position">Position</label>
                            <input type="text" class="form-control" name="position" id="position">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Faculty Modals for each faculty -->
    @foreach ($faculties as $faculty)
        <div class="modal fade" id="editFacultyModal-{{ $faculty->id }}" tabindex="-1" role="dialog" aria-labelledby="editFacultyModalLabel-{{ $faculty->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title" id="editFacultyModalLabel-{{ $faculty->id }}">Edit Faculty</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('admin.faculty.update', $faculty->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="id_number">ID Number</label>
                                <input type="text" class="form-control" name="id_number" value="{{ $faculty->id_number }}" required>
                            </div>
                            <div class="form-group">
                                <label for="first_name">First Name</label>
                                <input type="text" class="form-control" name="first_name" value="{{ $faculty->first_name }}" required>
                            </div>
                            <div class="form-group">
                                <label for="middle_initial">Middle Initial</label>
                                <input type="text" class="form-control" name="middle_initial" value="{{ $faculty->middle_initial }}">
                            </div>
                            <div class="form-group">
                                <label for="last_name">Last Name</label>
                                <input type="text" class="form-control" name="last_name" value="{{ $faculty->last_name }}" required>
                            </div>
                            <div class="form-group">
                                <label for="position">Position</label>
                                <input type="text" class="form-control" name="position" value="{{ $faculty->position }}">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection
