<div class="page-wrapper">
    <div class="page-content-tab">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <div class="float-end">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a wire:navigate href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Client Portal</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Client Portal</h4>
                    </div>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="text-muted">Client Meetings</div>
                            <div class="display-6">{{ $clientMeetings->count() }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="text-muted">Tracked Deliverables</div>
                            <div class="display-6">{{ $taskRows->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header"><h5 class="mb-0">Recent Client Meetings</h5></div>
                <div class="card-body table-responsive">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Client</th>
                                <th>Meeting Type</th>
                                <th>Date</th>
                                <th>Contact</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($clientMeetings as $meeting)
                                <tr>
                                    <td>{{ $meeting->client_name ?: 'N/A' }}</td>
                                    <td>{{ $meeting->meeting_type }}</td>
                                    <td>{{ optional($meeting->meeting_date)->format('d/m/Y') }}</td>
                                    <td>{{ $meeting->contact_number ?: 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No client meetings recorded yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h5 class="mb-0">Summary / Deadline / Details / Status / Uploaded</h5></div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Meeting</th>
                                <th>Summary</th>
                                <th>Deadline</th>
                                <th>Details</th>
                                <th>Status</th>
                                <th>Uploaded</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($taskRows as $task)
                                <tr>
                                    <td>{{ $task['meeting'] }}</td>
                                    <td>{{ $task['summary'] ?: 'N/A' }}</td>
                                    <td>{{ $task['deadline'] ? \Illuminate\Support\Carbon::parse($task['deadline'])->format('d/m/Y') : 'N/A' }}</td>
                                    <td>{{ $task['details'] ?: 'N/A' }}</td>
                                    <td>{{ $task['status'] }}</td>
                                    <td>{{ $task['uploaded'] ? 'Yes' : 'No' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No deliverables tracked yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <livewire:layout.footer />
    </div>
</div>
