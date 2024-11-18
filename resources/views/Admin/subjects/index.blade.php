@extends('layouts.admin')

@section('title', 'Manage Subjects')

@section('content')
<div class="container mt-4">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#addSubjectModal">
        Add Subject
    </button>

    <table class="table table-hover table-striped">
        <thead class="thead-dark">
            <tr>
                <th>Subject ID</th>
                <th>Subject Code</th>
                <th>Description</th>
                <th>Type</th>
                <th>Credit Units</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($subjects as $subject)
                <tr>
                    <td>{{ $subject->subject_id }}</td>
                    <td>{{ $subject->subject_code }}</td>
                    <td>{{ $subject->subject_description }}</td>
                    <td>{{ $subject->type }}</td>
                    <td>{{ $subject->credit_units }}</td>
                    <td>
                        <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editSubjectModal-{{ $subject->id }}">
                            Edit
                        </button>
                        <form action="{{ route('admin.subjects.destroy', $subject->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Add Subject Modal -->
    <div class="modal fade" id="addSubjectModal" tabindex="-1" role="dialog" aria-labelledby="addSubjectModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addSubjectModalLabel">Add Subject</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.subjects.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="subject_id">Subject ID</label>
                            <input type="text" class="form-control" name="subject_id" required>
                        </div>
                        <div class="form-group">
                            <label for="subject_code">Subject Code</label>
                            <input type="text" class="form-control" name="subject_code" required>
                        </div>
                        <div class="form-group">
                            <label for="subject_description">Description</label>
                            <input type="text" class="form-control" name="subject_description" required>
                        </div>
                        <div class="form-group">
                            <label for="type">Type</label>
                            <select class="form-control" name="type" required>
                                <option value="Lec">Lec</option>
                                <option value="Lab">Lab</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="credit_units">Credit Units</label>
                            <input type="number" class="form-control" name="credit_units" min="1" max="10" required>
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

    <!-- Edit Subject Modals -->
    @foreach ($subjects as $subject)
        <div class="modal fade" id="editSubjectModal-{{ $subject->id }}" tabindex="-1" role="dialog" aria-labelledby="editSubjectModalLabel-{{ $subject->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title" id="editSubjectModalLabel-{{ $subject->id }}">Edit Subject</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('admin.subjects.update', $subject->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="subject_id">Subject ID</label>
                                <input type="text" class="form-control" name="subject_id" value="{{ $subject->subject_id }}" required>
                            </div>
                            <div class="form-group">
                                <label for="subject_code">Subject Code</label>
                                <input type="text" class="form-control" name="subject_code" value="{{ $subject->subject_code }}" required>
                            </div>
                            <div class="form-group">
                                <label for="subject_description">Description</label>
                                <input type="text" class="form-control" name="subject_description" value="{{ $subject->subject_description }}" required>
                            </div>
                            <div class="form-group">
                                <label for="type">Type</label>
                                <select class="form-control" name="type" required>
                                    <option value="Lec" {{ $subject->type == 'Lec' ? 'selected' : '' }}>Lec</option>
                                    <option value="Lab" {{ $subject->type == 'Lab' ? 'selected' : '' }}>Lab</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="credit_units">Credit Units</label>
                                <input type="number" class="form-control" name="credit_units" value="{{ $subject->credit_units }}" min="1" max="10" required>
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
