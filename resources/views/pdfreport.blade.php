<x-filament-panels::page>
<link rel="stylesheet" href="{{ URL::to('bower_components/css/bootstrap.mins.css')}}">

<p class="text-center">
    UNIVERSITY OF PROFESSIONAL STUDIES CLINIC <br>
    {{ strtoupper($report_type) }}
</p>

<hr>

<div class="card-body table-responsive p-0">
    <table class="table table-hover table-table table-head-fixed">
        <thead>
            <tr>
                <th>ID</th>
                <th>Patient</th>
                <th>Doctor</th>
                <th>Specialities</th>
                <th>Appointment date</th>
                <th>status</th>
                <th>Arrival status</th>
            </tr>
        </thead>
        <tbody>
        @foreach($reports as $report)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $report->user?->name }}</td>
                <td>{{ $report->doctor?->full_name}}</td>
                <td>{{ $report->specialists?->pluck('name')}}</td>
                <td>{{ $report->appointment_datetime }}</td>
                <td>{{ $report->status }}</td>
                <td>{{ $report->arrival_status }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>



 <table class="table table-hover table-table table-head-fixed">
        <thead>
            <tr>
                <th>ID</th>
                <th>Patient</th>
                <th>Doctor</th>
                <th>Task name</th>
                <th>Deadline</th>
                <th>status</th>
                <th>Priority</th>
            </tr>
        </thead>
        <tbody>
        @foreach($reports as $report)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $report->patient?->full_name }}</td>
                <td>{{ $report->doctor?->full_name}}</td>
                <td>{{ $report->task_name}}</td>
                <td>{{ $report->deadline }}</td>
                <td>{{ $report->status }}</td>
                <td>{{ $report->priority }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>



<a href="#" class="btn btn-info" target="_blank">Print</a>

</div>
</x-filament-panels::page>