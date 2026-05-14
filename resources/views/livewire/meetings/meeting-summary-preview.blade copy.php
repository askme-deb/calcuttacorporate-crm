<div class="page-wrapper">
    <div class="page-content-tab">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <div class="float-end">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('meetings.summary') }}">Meeting Summary</a></li>
                                <li class="breadcrumb-item active">Preview</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Meeting Summary Preview</h4>
                    </div>
                </div>
            </div>

            <div class="d-flex flex-wrap gap-2 mb-3">
                <a href="{{ route('meetings.summary') }}" class="btn btn-outline-secondary">Back to List</a>
                <a href="{{ route('meetings.summary.edit', $meetingRecord->id) }}" class="btn btn-primary">Edit Meeting</a>
            </div>

            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Meeting Overview</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="small text-muted">Meeting Type</div>
                            <div>{{ $meetingRecord->meeting_type ?: 'N/A' }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="small text-muted">Meeting Mode</div>
                            <div>{{ $meetingRecord->meeting_mode ?: 'N/A' }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="small text-muted">Meeting Date</div>
                            <div>{{ optional($meetingRecord->meeting_date)->format('d/m/Y') ?: 'N/A' }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="small text-muted">Department</div>
                            <div>{{ $meetingRecord->department ?: 'N/A' }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="small text-muted">Start Time</div>
                            <div>{{ $meetingRecord->start_time ?: 'N/A' }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="small text-muted">End Time</div>
                            <div>{{ $meetingRecord->end_time ?: 'N/A' }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="small text-muted">Location</div>
                            <div>{{ $meetingRecord->meeting_location ?: 'N/A' }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="small text-muted">Created By</div>
                            <div>{{ optional($meetingRecord->creator)->name ?: 'System' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="small text-muted">Meeting Attended</div>
                            <div>{{ $meetingRecord->meeting_attended ?: 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Client And Contact Details</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="small text-muted">Client</div>
                            <div>{{ $meetingRecord->client_name ?: optional($meetingRecord->client)->client_name ?: 'N/A' }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="small text-muted">Company Name</div>
                            <div>{{ $meetingRecord->company_name ?: 'N/A' }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="small text-muted">Contact Number</div>
                            <div>{{ $meetingRecord->contact_number ?: 'N/A' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="small text-muted">Contact Person</div>
                            <div>{{ $meetingRecord->contact_person ?: 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Who Attended The Meeting</h5>
                </div>
                <div class="card-body">
                    @forelse ($meetingRecord->attendees ?? [] as $attendee)
                        <div class="border rounded px-3 py-2 mb-2">{{ $attendee['employee_name'] ?: 'N/A' }}</div>
                    @empty
                        <div class="text-muted">No attendees recorded.</div>
                    @endforelse
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Meeting Agenda</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Agenda Point</th>
                                    <th>Department</th>
                                    <th>Priority</th>
                                    <th>Attendance Status</th>
                                    <th>Responsible Person</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($meetingRecord->agenda_items ?? [] as $agendaItem)
                                    <tr>
                                        <td>{{ $agendaItem['agenda_point'] ?: 'N/A' }}</td>
                                        <td>{{ $agendaItem['department'] ?: 'N/A' }}</td>
                                        <td>{{ $agendaItem['priority'] ?: 'N/A' }}</td>
                                        <td>{{ $agendaItem['attendance_status'] ?: 'N/A' }}</td>
                                        <td>{{ $agendaItem['responsible_person'] ?: 'N/A' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">No agenda items recorded.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Meeting Summary / Report</h5>
                </div>
                <div class="card-body">
                    <div class="mb-0" style="white-space: pre-line;">{{ $meetingRecord->discussion_point ?: 'N/A' }}</div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Followup Action Items / Task Assignment</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Task</th>
                                    <th>Assigned To</th>
                                    <th>Deadline</th>
                                    <th>Status</th>
                                    <th>Summary</th>
                                    <th>Details</th>
                                    <th>Uploaded</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($meetingRecord->action_items ?? [] as $actionItem)
                                    <tr>
                                        <td>{{ $actionItem['task'] ?: 'N/A' }}</td>
                                        <td>{{ $actionItem['assigned_to'] ?: 'N/A' }}</td>
                                        <td>{{ !empty($actionItem['deadline']) ? \Illuminate\Support\Carbon::parse($actionItem['deadline'])->format('d/m/Y') : 'N/A' }}</td>
                                        <td>{{ $actionItem['status'] ?: 'N/A' }}</td>
                                        <td>{{ $actionItem['summary'] ?: 'N/A' }}</td>
                                        <td>{{ $actionItem['details'] ?: 'N/A' }}</td>
                                        <td>{{ !empty($actionItem['uploaded']) ? 'Yes' : 'No' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">No action items recorded.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Next Follow-Up</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="small text-muted">Next Follow-Up Date</div>
                            <div>{{ optional($meetingRecord->next_follow_up_date)->format('d/m/Y') ?: 'N/A' }}</div>
                        </div>
                        <div class="col-md-8">
                            <div class="small text-muted">Follow-Up Action Summary</div>
                            <div>{{ $meetingRecord->followup_action ?: 'N/A' }}</div>
                        </div>
                        <div class="col-md-12">
                            <div class="small text-muted">Next Follow-Up Details</div>
                            <div style="white-space: pre-line;">{{ $meetingRecord->next_follow_up_details ?: 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Attachments</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Attachment Type</th>
                                    <th>Status</th>
                                    <th>File</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($meetingRecord->attachments ?? [] as $attachment)
                                    <tr>
                                        <td>{{ $attachment['attachment_type'] ?: 'N/A' }}</td>
                                        <td>{{ !empty($attachment['file_path']) ? 'Uploaded' : 'Pending' }}</td>
                                        <td>
                                            @if (!empty($attachment['file_path']))
                                                <a href="{{ Storage::url($attachment['file_path']) }}" target="_blank">
                                                    {{ $attachment['original_name'] ?: 'View attachment' }}
                                                </a>
                                            @else
                                                <span class="text-muted">No file</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">No attachments configured.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <livewire:layout.footer />
    </div>
</div>
