@extends('layouts.admin')

@section('title', 'Manage Exam Rooms')

@section('content')
<div class="container">
    <h3>Exam Room Schedule</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Time Slots</th>
                @foreach ($rooms as $room)
                    <th>{{ $room->room_name }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($timeSlots as $timeSlot)
                <tr>
                    <td>{{ $timeSlot }}</td>
                    @foreach ($rooms as $room)
                        <td id="room-{{ $room->id }}-time-{{ str_replace(':', '', $timeSlot) }}">
                            <!-- Schedule details will be dynamically populated -->
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection


@push('scripts')


</script>
@endpush
