<!-- Faculty List Modal -->
<div class="modal fade" id="facultyListModal" tabindex="-1" aria-labelledby="facultyListModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="facultyListModalLabel">Select Faculty</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-hover table-bordered" id="facultyTable">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($faculties as $faculty)
                        <tr>
                            <td>{{ $faculty->first_name }} {{ $faculty->last_name }}</td>
                            <td>{{ $faculty->position }}</td>
                            <td>
                                <button 
                                    class="btn btn-primary btn-sm select-faculty" 
                                    data-id="{{ $faculty->id }}"
                                    data-name="{{ $faculty->first_name }} {{ $faculty->last_name }}"
                                    data-position="{{ $faculty->position }}"
                                    data-bs-dismiss="modal"
                                >
                                    Select
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    // JavaScript to handle selecting a faculty
    document.addEventListener('DOMContentLoaded', function () {
        const facultyButtons = document.querySelectorAll('.select-faculty');
        facultyButtons.forEach(button => {
            button.addEventListener('click', function () {
                const facultyName = this.dataset.name;
                const facultyPosition = this.dataset.position;

                // Populate the Add Schedule modal fields
                document.getElementById('facultyNameInput').value = facultyName;
                document.getElementById('facultyPositionInput').value = facultyPosition;
            });
        });
    });
</script>
