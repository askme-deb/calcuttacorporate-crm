<div class="page-wrapper">
    <div class="page-content-tab">
        <div class="container-fluid">

            {{-- Page Header --}}
            <div class="row mb-4">
                <div class="col-12">
                    <div class="msp-page-header">
                        <div class="msp-header-left">
                            <span class="msp-module-label">Meetings</span>
                            <h4 class="msp-page-title">Meeting Summary Preview</h4>
                        </div>
                        <div class="msp-header-right">
                            <ol class="msp-breadcrumb">
                                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li class="sep"><i class="fas fa-chevron-right"></i></li>
                                <li><a href="{{ route('meetings.summary') }}">Meeting Summary</a></li>
                                <li class="sep"><i class="fas fa-chevron-right"></i></li>
                                <li class="active">Preview</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Action Bar --}}
            <div class="msp-action-bar mb-4">
                <a href="{{ route('meetings.summary') }}" class="msp-btn msp-btn-ghost">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
                <a href="{{ route('meetings.summary.pdf', $meetingRecord->id) }}" class="msp-btn msp-btn-ghost">
                    <i class="fas fa-file-pdf"></i> Download PDF
                </a>
                <a href="{{ route('meetings.summary.print', $meetingRecord->id) }}" target="_blank" class="msp-btn msp-btn-dark">
                    <i class="fas fa-print"></i> Print Version
                </a>
                <a href="{{ route('meetings.summary.edit', $meetingRecord->id) }}" class="msp-btn msp-btn-primary">
                    <i class="fas fa-pen"></i> Edit Meeting
                </a>
            </div>

            {{-- 1 Meeting Overview --}}
            <div class="msp-card mb-4">
                <div class="msp-card-header">
                    <div class="msp-section-icon msp-icon-blue"><i class="fas fa-calendar-check"></i></div>
                    <h5 class="msp-section-title">Meeting Overview</h5>
                </div>
                <div class="msp-card-body">
                    <div class="msp-grid">
                        <div class="msp-field">
                            <span class="msp-label">Meeting Type</span>
                            <span class="msp-value"><span class="msp-badge msp-badge-blue">{{ $meetingRecord->meeting_type ?: 'N/A' }}</span></span>
                        </div>
                        <div class="msp-field">
                            <span class="msp-label">Meeting Mode</span>
                            <span class="msp-value">
                                @if($meetingRecord->meeting_mode === 'Online')
                                    <span class="msp-badge msp-badge-teal"><i class="fas fa-wifi"></i> Online</span>
                                @elseif($meetingRecord->meeting_mode === 'Offline')
                                    <span class="msp-badge msp-badge-neutral"><i class="fas fa-building"></i> Offline</span>
                                @else
                                    <span class="msp-text-muted">N/A</span>
                                @endif
                            </span>
                        </div>
                        <div class="msp-field">
                            <span class="msp-label">Meeting Date</span>
                            <span class="msp-value msp-value-date">
                                <i class="fas fa-calendar-alt"></i>
                                {{ optional($meetingRecord->meeting_date)->format('d M Y') ?: 'N/A' }}
                            </span>
                        </div>
                        <div class="msp-field">
                            <span class="msp-label">Department</span>
                            <span class="msp-value">
                                @if($meetingRecord->department)
                                    <span class="msp-badge msp-badge-neutral">{{ $meetingRecord->department }}</span>
                                @else
                                    <span class="msp-text-muted">N/A</span>
                                @endif
                            </span>
                        </div>
                        <div class="msp-field">
                            <span class="msp-label">Start Time</span>
                            <span class="msp-value msp-value-date"><i class="fas fa-clock"></i> {{ $meetingRecord->start_time ?: 'N/A' }}</span>
                        </div>
                        <div class="msp-field">
                            <span class="msp-label">End Time</span>
                            <span class="msp-value msp-value-date"><i class="fas fa-clock"></i> {{ $meetingRecord->end_time ?: 'N/A' }}</span>
                        </div>
                        <div class="msp-field">
                            <span class="msp-label">Location</span>
                            <span class="msp-value">
                                <i class="fas fa-map-marker-alt" style="color:var(--msp-text-muted);font-size:12px;margin-right:5px;"></i>
                                {{ $meetingRecord->meeting_location ?: 'N/A' }}
                            </span>
                        </div>
                        <div class="msp-field">
                            <span class="msp-label">Created By</span>
                            <span class="msp-value">
                                <div class="msp-creator">
                                    <div class="msp-avatar">{{ strtoupper(substr(optional($meetingRecord->creator)->name ?: 'S', 0, 1)) }}</div>
                                    {{ optional($meetingRecord->creator)->name ?: 'System' }}
                                </div>
                            </span>
                        </div>
                        <div class="msp-field msp-field-wide">
                            <span class="msp-label">Meeting Attended</span>
                            <span class="msp-value">{{ $meetingRecord->meeting_attended ?: 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2 Client & Contact --}}
            <div class="msp-card mb-4">
                <div class="msp-card-header">
                    <div class="msp-section-icon msp-icon-purple"><i class="fas fa-user-tie"></i></div>
                    <h5 class="msp-section-title">Client &amp; Contact Details</h5>
                </div>
                <div class="msp-card-body">
                    <div class="msp-grid">
                        <div class="msp-field">
                            <span class="msp-label">Client</span>
                            <span class="msp-value msp-value-strong">{{ $meetingRecord->client_name ?: optional($meetingRecord->client)->client_name ?: 'N/A' }}</span>
                        </div>
                        <div class="msp-field">
                            <span class="msp-label">Company Name</span>
                            <span class="msp-value msp-value-strong">{{ $meetingRecord->company_name ?: 'N/A' }}</span>
                        </div>
                        <div class="msp-field">
                            <span class="msp-label">Contact Number</span>
                            <span class="msp-value">
                                @if($meetingRecord->contact_number)
                                    <a href="tel:{{ $meetingRecord->contact_number }}" class="msp-link"><i class="fas fa-phone"></i> {{ $meetingRecord->contact_number }}</a>
                                @else
                                    <span class="msp-text-muted">N/A</span>
                                @endif
                            </span>
                        </div>
                        <div class="msp-field">
                            <span class="msp-label">Contact Person</span>
                            <span class="msp-value">{{ $meetingRecord->contact_person ?: 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3 Attendees --}}
            <div class="msp-card mb-4">
                <div class="msp-card-header">
                    <div class="msp-section-icon msp-icon-green"><i class="fas fa-users"></i></div>
                    <h5 class="msp-section-title">Who Attended The Meeting</h5>
                    @php $attendees = $meetingRecord->attendees ?? []; @endphp
                    @if(count($attendees))
                        <span class="msp-count-badge">{{ count($attendees) }}</span>
                    @endif
                </div>
                <div class="msp-card-body">
                    @forelse ($attendees as $attendee)
                        <div class="msp-attendee-chip">
                            <div class="msp-attendee-avatar">{{ strtoupper(substr($attendee['employee_name'] ?: 'A', 0, 1)) }}</div>
                            <span>{{ $attendee['employee_name'] ?: 'N/A' }}</span>
                        </div>
                    @empty
                        <div class="msp-empty-inline">No attendees recorded.</div>
                    @endforelse
                </div>
            </div>

            {{-- 4 Agenda --}}
            <div class="msp-card mb-4">
                <div class="msp-card-header">
                    <div class="msp-section-icon msp-icon-orange"><i class="fas fa-list-ul"></i></div>
                    <h5 class="msp-section-title">Meeting Agenda</h5>
                </div>
                <div class="msp-card-body msp-card-body-table">
                    <div class="table-responsive">
                        <table class="msp-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Agenda Point</th>
                                    <th>Department</th>
                                    <th>Priority</th>
                                    <th>Attendance</th>
                                    <th>Responsible Person</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($meetingRecord->agenda_items ?? [] as $i => $agendaItem)
                                    <tr>
                                        <td class="msp-td-index">{{ $i + 1 }}</td>
                                        <td class="msp-td-main">{{ $agendaItem['agenda_point'] ?: 'N/A' }}</td>
                                        <td>
                                            @if(!empty($agendaItem['department']))
                                                <span class="msp-badge msp-badge-neutral">{{ $agendaItem['department'] }}</span>
                                            @else &mdash; @endif
                                        </td>
                                        <td>
                                            @php $p = $agendaItem['priority'] ?? ''; @endphp
                                            @if($p==='High') <span class="msp-badge msp-badge-red">High</span>
                                            @elseif($p==='Medium') <span class="msp-badge msp-badge-yellow">Medium</span>
                                            @elseif($p==='Low') <span class="msp-badge msp-badge-green">Low</span>
                                            @else <span class="msp-text-muted">&mdash;</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php $as = $agendaItem['attendance_status'] ?? ''; @endphp
                                            @if($as==='Present') <span class="msp-badge msp-badge-green">Present</span>
                                            @elseif($as==='Absent') <span class="msp-badge msp-badge-red">Absent</span>
                                            @elseif($as==='Pending') <span class="msp-badge msp-badge-yellow">Pending</span>
                                            @else <span class="msp-text-muted">&mdash;</span>
                                            @endif
                                        </td>
                                        <td>{{ $agendaItem['responsible_person'] ?: '&mdash;' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="msp-td-empty">No agenda items recorded.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- 5 Summary / Report --}}
            <div class="msp-card mb-4">
                <div class="msp-card-header">
                    <div class="msp-section-icon msp-icon-indigo"><i class="fas fa-file-alt"></i></div>
                    <h5 class="msp-section-title">Meeting Summary / Report</h5>
                </div>
                <div class="msp-card-body">
                    @if($meetingRecord->discussion_point)
                        <div class="msp-prose">{{ $meetingRecord->discussion_point }}</div>
                    @else
                        <div class="msp-empty-inline">No discussion points recorded.</div>
                    @endif
                </div>
            </div>

            {{-- 6 Action Items --}}
            <div class="msp-card mb-4">
                <div class="msp-card-header">
                    <div class="msp-section-icon msp-icon-red"><i class="fas fa-tasks"></i></div>
                    <h5 class="msp-section-title">Followup Action Items / Task Assignment</h5>
                </div>
                <div class="msp-card-body msp-card-body-table">
                    <div class="table-responsive">
                        <table class="msp-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Task</th>
                                    <th>Assigned To</th>
                                    <th>Deadline</th>
                                    <th>Status</th>
                                    <th>Summary</th>
                                    <th>Details</th>
                                    <th class="text-center">Uploaded</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($meetingRecord->action_items ?? [] as $i => $actionItem)
                                    <tr>
                                        <td class="msp-td-index">{{ $i + 1 }}</td>
                                        <td class="msp-td-main">{{ $actionItem['task'] ?: 'N/A' }}</td>
                                        <td>{{ $actionItem['assigned_to'] ?: '&mdash;' }}</td>
                                        <td>
                                            @if(!empty($actionItem['deadline']))
                                                <span class="msp-value-date"><i class="fas fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($actionItem['deadline'])->format('d M Y') }}</span>
                                            @else
                                                <span class="msp-text-muted">&mdash;</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php $st = $actionItem['status'] ?? ''; @endphp
                                            @if($st==='Completed') <span class="msp-badge msp-badge-green">Completed</span>
                                            @elseif($st==='In Progress') <span class="msp-badge msp-badge-blue">In Progress</span>
                                            @elseif($st==='Blocked') <span class="msp-badge msp-badge-red">Blocked</span>
                                            @elseif($st==='Pending') <span class="msp-badge msp-badge-yellow">Pending</span>
                                            @else <span class="msp-text-muted">&mdash;</span>
                                            @endif
                                        </td>
                                        <td>{{ $actionItem['summary'] ?: '&mdash;' }}</td>
                                        <td>{{ $actionItem['details'] ?: '&mdash;' }}</td>
                                        <td class="text-center">
                                            @if(!empty($actionItem['uploaded']))
                                                <span class="msp-uploaded-yes"><i class="fas fa-check-circle"></i></span>
                                            @else
                                                <span class="msp-uploaded-no"><i class="fas fa-minus-circle"></i></span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="8" class="msp-td-empty">No action items recorded.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- 7 Next Follow-Up --}}
            <div class="msp-card mb-4">
                <div class="msp-card-header">
                    <div class="msp-section-icon msp-icon-teal"><i class="fas fa-redo-alt"></i></div>
                    <h5 class="msp-section-title">Next Follow-Up</h5>
                </div>
                <div class="msp-card-body">
                    <div class="msp-grid">
                        <div class="msp-field">
                            <span class="msp-label">Next Follow-Up Date</span>
                            <span class="msp-value msp-value-date">
                                <i class="fas fa-calendar-alt"></i>
                                {{ optional($meetingRecord->next_follow_up_date)->format('d M Y') ?: 'N/A' }}
                            </span>
                        </div>
                        <div class="msp-field msp-field-wide">
                            <span class="msp-label">Follow-Up Action Summary</span>
                            <span class="msp-value">{{ $meetingRecord->followup_action ?: 'N/A' }}</span>
                        </div>
                        <div class="msp-field msp-field-full">
                            <span class="msp-label">Next Follow-Up Details</span>
                            @if($meetingRecord->next_follow_up_details)
                                <div class="msp-prose">{{ $meetingRecord->next_follow_up_details }}</div>
                            @else
                                <span class="msp-text-muted">N/A</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- 8 Attachments --}}
            <div class="msp-card mb-4">
                <div class="msp-card-header">
                    <div class="msp-section-icon msp-icon-slate"><i class="fas fa-paperclip"></i></div>
                    <h5 class="msp-section-title">Attachments</h5>
                </div>
                <div class="msp-card-body msp-card-body-table">
                    <div class="table-responsive">
                        <table class="msp-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Attachment Type</th>
                                    <th>Status</th>
                                    <th>File</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($meetingRecord->attachments ?? [] as $i => $attachment)
                                    <tr>
                                        <td class="msp-td-index">{{ $i + 1 }}</td>
                                        <td class="msp-td-main">{{ $attachment['attachment_type'] ?: 'N/A' }}</td>
                                        <td>
                                            @if(!empty($attachment['file_path']))
                                                <span class="msp-badge msp-badge-green"><i class="fas fa-check"></i> Uploaded</span>
                                            @else
                                                <span class="msp-badge msp-badge-yellow">Pending</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(!empty($attachment['file_path']))
                                                <a href="{{ Storage::url($attachment['file_path']) }}" target="_blank" class="msp-file-link">
                                                    <i class="fas fa-external-link-alt"></i>
                                                    {{ $attachment['original_name'] ?: 'View attachment' }}
                                                </a>
                                            @else
                                                <span class="msp-text-muted">No file uploaded</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="msp-td-empty">No attachments configured.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
        <livewire:layout.footer />
    </div>

<style>
:root {
    --msp-primary:       #2563eb;
    --msp-primary-dim:   #dbeafe;
    --msp-primary-dark:  #1d4ed8;
    --msp-border:        #e8ecf4;
    --msp-border-light:  #f0f3fa;
    --msp-card-bg:       #ffffff;
    --msp-section-bg:    #fafbff;
    --msp-text-head:     #0f172a;
    --msp-text-body:     #374151;
    --msp-text-muted:    #9ca3af;
    --msp-text-label:    #6b7280;
    --msp-radius-sm:     6px;
    --msp-radius:        10px;
    --msp-radius-lg:     14px;
    --msp-shadow:        0 4px 16px rgba(0,0,0,.07), 0 1px 4px rgba(0,0,0,.04);
}
.msp-page-header { display:flex; align-items:flex-end; justify-content:space-between; padding:8px 0 4px; flex-wrap:wrap; gap:8px; }
.msp-module-label { display:block; font-size:11px; font-weight:600; letter-spacing:.08em; text-transform:uppercase; color:var(--msp-primary); margin-bottom:2px; }
.msp-page-title { margin:0; font-size:22px; font-weight:700; color:var(--msp-text-head); letter-spacing:-.3px; }
.msp-breadcrumb { display:flex; align-items:center; gap:6px; list-style:none; margin:0; padding:0; font-size:12.5px; color:var(--msp-text-muted); }
.msp-breadcrumb a { color:var(--msp-primary); text-decoration:none; }
.msp-breadcrumb a:hover { text-decoration:underline; }
.msp-breadcrumb .sep { font-size:9px; color:var(--msp-border); }
.msp-breadcrumb .active { color:var(--msp-text-label); font-weight:500; }
.msp-action-bar { display:flex; gap:10px; flex-wrap:wrap; }
.msp-btn { display:inline-flex; align-items:center; gap:7px; height:38px; padding:0 18px; border-radius:var(--msp-radius); font-size:13.5px; font-weight:600; text-decoration:none; cursor:pointer; border:none; transition:all .15s; white-space:nowrap; }
.msp-btn-primary { background:var(--msp-primary); color:#fff !important; box-shadow:0 2px 6px rgba(37,99,235,.3); }
.msp-btn-primary:hover { background:var(--msp-primary-dark); transform:translateY(-1px); box-shadow:0 4px 12px rgba(37,99,235,.35); color:#fff !important; }
.msp-btn-dark { background:#0f172a; color:#fff !important; box-shadow:0 2px 6px rgba(15,23,42,.2); }
.msp-btn-dark:hover { background:#1e293b; transform:translateY(-1px); color:#fff !important; }
.msp-btn-ghost { background:#fff; color:var(--msp-text-body) !important; border:1px solid var(--msp-border); box-shadow:0 1px 3px rgba(0,0,0,.05); }
.msp-btn-ghost:hover { background:var(--msp-section-bg); border-color:#c9d0e0; transform:translateY(-1px); color:var(--msp-text-body) !important; }
.msp-card { background:var(--msp-card-bg); border:1px solid var(--msp-border); border-radius:var(--msp-radius-lg); box-shadow:var(--msp-shadow); overflow:hidden; }
.msp-card-header { display:flex; align-items:center; gap:12px; padding:16px 24px; border-bottom:1px solid var(--msp-border-light); background:var(--msp-section-bg); }
.msp-section-title { margin:0; font-size:14.5px; font-weight:700; color:var(--msp-text-head); letter-spacing:-.1px; }
.msp-card-body { padding:24px; }
.msp-card-body-table { padding:0; }
.msp-section-icon { width:34px; height:34px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:14px; flex-shrink:0; }
.msp-icon-blue   { background:#dbeafe; color:#2563eb; }
.msp-icon-purple { background:#ede9fe; color:#7c3aed; }
.msp-icon-green  { background:#dcfce7; color:#16a34a; }
.msp-icon-orange { background:#ffedd5; color:#ea580c; }
.msp-icon-indigo { background:#e0e7ff; color:#4338ca; }
.msp-icon-red    { background:#fee2e2; color:#dc2626; }
.msp-icon-teal   { background:#ccfbf1; color:#0d9488; }
.msp-icon-slate  { background:#f1f5f9; color:#475569; }
.msp-count-badge { margin-left:auto; background:var(--msp-primary-dim); color:var(--msp-primary); font-size:11.5px; font-weight:700; padding:2px 9px; border-radius:20px; }
.msp-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:20px 24px; }
@media(max-width:992px){ .msp-grid { grid-template-columns:repeat(2,1fr); } }
@media(max-width:576px){ .msp-grid { grid-template-columns:1fr; } }
.msp-field { display:flex; flex-direction:column; gap:5px; }
.msp-field-wide { grid-column:span 2; }
.msp-field-full  { grid-column:1/-1; }
.msp-label { font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:.07em; color:var(--msp-text-muted); }
.msp-value { font-size:14px; color:var(--msp-text-body); }
.msp-value-strong { font-weight:600; color:var(--msp-text-head); }
.msp-value-date { display:inline-flex; align-items:center; gap:6px; font-size:14px; color:var(--msp-text-body); font-variant-numeric:tabular-nums; }
.msp-value-date i { color:var(--msp-text-muted); font-size:12px; }
.msp-text-muted { color:var(--msp-text-muted); font-size:13.5px; }
.msp-creator { display:inline-flex; align-items:center; gap:8px; font-size:14px; }
.msp-avatar { width:28px; height:28px; border-radius:50%; background:linear-gradient(135deg,var(--msp-primary) 0%,#60a5fa 100%); color:#fff; font-size:11px; font-weight:700; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.msp-attendee-chip { display:inline-flex; align-items:center; gap:8px; background:#f8faff; border:1px solid var(--msp-border); border-radius:30px; padding:6px 14px 6px 6px; margin:0 8px 8px 0; font-size:13.5px; color:var(--msp-text-body); font-weight:500; }
.msp-attendee-avatar { width:26px; height:26px; border-radius:50%; background:linear-gradient(135deg,#7c3aed 0%,#a78bfa 100%); color:#fff; font-size:11px; font-weight:700; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.msp-badge { display:inline-flex; align-items:center; gap:4px; padding:3px 10px; border-radius:20px; font-size:12px; font-weight:600; white-space:nowrap; }
.msp-badge-blue    { background:#dbeafe; color:#1d4ed8; }
.msp-badge-teal    { background:#ccfbf1; color:#0d9488; }
.msp-badge-green   { background:#dcfce7; color:#16a34a; }
.msp-badge-red     { background:#fee2e2; color:#dc2626; }
.msp-badge-yellow  { background:#fef3c7; color:#b45309; }
.msp-badge-neutral { background:#f3f4f6; color:#374151; border:1px solid #e5e7eb; }
.msp-table { width:100%; border-collapse:collapse; font-size:13.5px; }
.msp-table thead tr { background:#f8faff; border-bottom:2px solid var(--msp-border); }
.msp-table thead th { padding:12px 18px; font-size:11.5px; font-weight:700; letter-spacing:.06em; text-transform:uppercase; color:var(--msp-text-label); white-space:nowrap; }
.msp-table tbody tr { border-bottom:1px solid var(--msp-border-light); transition:background .12s; }
.msp-table tbody tr:last-child { border-bottom:none; }
.msp-table tbody tr:hover { background:#f8faff; }
.msp-table tbody td { padding:13px 18px; color:var(--msp-text-body); vertical-align:middle; }
.msp-td-index { color:var(--msp-text-muted); font-size:12.5px; font-weight:600; width:36px; }
.msp-td-main { font-weight:500; color:var(--msp-text-head); }
.msp-td-empty { text-align:center; color:var(--msp-text-muted); padding:32px !important; font-size:13px; }
.msp-prose { white-space:pre-line; font-size:14px; color:var(--msp-text-body); line-height:1.7; background:#f8faff; border:1px solid var(--msp-border-light); border-radius:var(--msp-radius); padding:16px 18px; }
.msp-empty-inline { color:var(--msp-text-muted); font-size:13px; font-style:italic; }
.msp-link { color:var(--msp-primary); text-decoration:none; font-size:14px; }
.msp-link:hover { text-decoration:underline; }
.msp-file-link { display:inline-flex; align-items:center; gap:6px; color:var(--msp-primary); font-size:13.5px; text-decoration:none; font-weight:500; }
.msp-file-link:hover { text-decoration:underline; }
.msp-file-link i { font-size:11px; }
.msp-uploaded-yes { color:#16a34a; font-size:16px; }
.msp-uploaded-no  { color:#d1d5db; font-size:16px; }
@media(max-width:768px){ .msp-page-header { flex-direction:column; align-items:flex-start; } .msp-card-body { padding:16px; } }
</style>

</div>
