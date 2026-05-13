<div class="page-wrapper">
    <div class="page-content-tab">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <div class="float-end">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a wire:navigate href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Advocate Portal</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Advocate Portal</h4>
                    </div>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="text-muted">Legal Meetings</div>
                            <div class="display-6">{{ $legalMeetings->count() }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="text-muted">Upcoming Follow-Ups</div>
                            <div class="display-6">{{ $upcomingFollowups->count() }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="text-muted">Meeting Summary Access</div>
                            <a wire:navigate href="{{ route('meetings.summary') }}" class="btn btn-primary mt-2">Open Meeting Summary</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header"><h5 class="mb-0">Recent Legal Meetings</h5></div>
                <div class="card-body table-responsive">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Meeting</th>
                                <th>Date</th>
                                <th>Client</th>
                                <th>Location</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($legalMeetings as $meeting)
                                <tr>
                                    <td>{{ $meeting->meeting_type }}</td>
                                    <td>{{ optional($meeting->meeting_date)->format('d/m/Y') }}</td>
                                    <td>{{ $meeting->client_name ?: 'N/A' }}</td>
                                    <td>{{ $meeting->meeting_location ?: 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No legal meetings recorded yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h5 class="mb-0">Upcoming Legal Follow-Ups</h5></div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @forelse ($upcomingFollowups as $meeting)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $meeting->client_name ?: $meeting->meeting_type }}</span>
                                <span>{{ optional($meeting->next_follow_up_date)->format('d/m/Y') }}</span>
                            </li>
                        @empty
                            <li class="list-group-item text-center">No upcoming follow-ups.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
        <livewire:layout.footer />
    </div>
</div>
