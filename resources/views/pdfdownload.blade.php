<link rel="stylesheet" href="{{ URL::to('css/bootstrap.mins.css')}}">

<h2 class="text-center">
UNIVERSITY OF PROFESSIONAL STUDIES CLINIC <br>
{{ strtoupper($report_type) }} REPORT
</h2>

<hr>

@if($report_type == "Appointments")
<div class="card-body table-responsive p-0">
<table class="table table-hover table-table table-head-fixed">
<thead>
<tr>
<th>ID</th>
<th>Patient</th>
<th>Doctor</th>
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
<td>{{ $report->appointment_datetime }}</td>
<td>{{ $report->status }}</td>
<td>{{ $report->arrival_status }}</td>
</tr>
@endforeach
</tbody>
</table>

@else

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

@endif


</div>