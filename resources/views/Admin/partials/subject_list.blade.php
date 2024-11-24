<!-- Subject List Modal -->
<div class="modal fade" id="subjectListModal" tabindex="-1" aria-labelledby="subjectListModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="subjectListModalLabel">Select Subject</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-hover table-bordered" id="subjectTable">
                    <thead>
                        <tr>
                            <th>Subject Code</th>
                            <th>Description</th>
                            <th>Units</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subjects as $subject)
                        <tr>
                            <td>{{ $subject->subject_code }}</td>
                            <td>{{ $subject->subject_description }}</td>
                            <td>{{ $subject->units }}</td>
                            <td>
                                <button 
                                    class="btn btn-primary btn-sm select-subject" 
                                    data-code="{{ $subject->subject_code }}"
                                    data-description="{{ $subject->subject_description }}"
                                    data-units="{{ $subject->units }}"
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
    // JavaScript to handle selecting a subject
    document.addEventListener('DOMContentLoaded', function () {
        const subjectButtons = document.querySelectorAll('.select-subject');
        subjectButtons.forEach(button => {
            button.addEventListener('click', function () {
                const subjectCode = this.dataset.code;
                const subjectDescription = this.dataset.description;

                // Populate the Add Schedule modal fields
                document.getElementById('subjectCodeInput').value = subjectCode;
            });
        });
    });
</script>
