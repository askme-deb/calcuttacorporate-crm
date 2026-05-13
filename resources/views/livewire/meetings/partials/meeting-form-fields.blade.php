{{-- This partial contains all meeting form fields, extracted for reuse in the standalone form --}}
<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">Meeting Type</label>
        <select class="form-select" wire:model="meeting.meeting_type">
            @foreach ($meetingTypes as $type)
                <option value="{{ $type }}">{{ $type }}</option>
            @endforeach
        </select>
        @error('meeting.meeting_type') <span class="text-danger small">{{ $message }}</span> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Meeting Mode</label>
        <select class="form-select" wire:model="meeting.meeting_mode">
            @foreach ($meetingModes as $mode)
                <option value="{{ $mode }}">{{ $mode }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Meeting Date</label>
        <input type="date" class="form-control" wire:model="meeting.meeting_date">
        @error('meeting.meeting_date') <span class="text-danger small">{{ $message }}</span> @enderror
    </div>
    <div class="col-md-3">
        <label class="form-label">Start Time</label>
        <input type="time" class="form-control" wire:model="meeting.start_time">
    </div>
    <div class="col-md-3">
        <label class="form-label">End Time</label>
        <input type="time" class="form-control" wire:model="meeting.end_time">
    </div>
    <div class="col-md-3">
        <label class="form-label">Department</label>
        <select class="form-select" wire:model="meeting.department">
            @foreach ($departments as $department)
                <option value="{{ $department }}">{{ $department }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Meeting Created By</label>
        <select class="form-select" wire:model="meeting.meeting_created_by">
            <option value="">Select Employee</option>
            @foreach ($employees as $employee)
                <option value="{{ $employee['id'] }}">{{ $employee['name'] }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Meeting Location</label>
        <input type="text" class="form-control" wire:model="meeting.meeting_location" placeholder="Office / Client Office / Google Meet / Zoom">
    </div>
    <div class="col-md-6">
        <label class="form-label">Meeting Attended</label>
        <input type="text" class="form-control" wire:model="meeting.meeting_attended" placeholder="Employee with Employee / Employee with Client">
    </div>
    <div class="col-md-4">
        <label class="form-label">Client</label>
        <select class="form-select" wire:model="meeting.client_id">
            <option value="">Select Client</option>
            @foreach ($clients as $client)
                <option value="{{ $client['id'] }}">{{ $client['client_name'] }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Client Name</label>
        <input type="text" class="form-control" wire:model="meeting.client_name">
    </div>
    <div class="col-md-4">
        <label class="form-label">Company Name</label>
        <input type="text" class="form-control" wire:model="meeting.company_name">
    </div>
    <div class="col-md-6">
        <label class="form-label">Contact Number</label>
        <input type="text" class="form-control" wire:model="meeting.contact_number">
    </div>
    <div class="col-md-6">
        <label class="form-label">Client / Employee Contact</label>
        <input type="text" class="form-control" wire:model="meeting.contact_person">
    </div>
</div>
<hr>
<div class="d-flex justify-content-between align-items-center mb-2">
    <h6 class="mb-0">Who Attended The Meeting</h6>
    <button type="button" class="btn btn-sm btn-outline-primary" wire:click="addAttendee">Add Attendee</button>
</div>
@foreach ($meeting['attendees'] as $index => $attendee)
    <div class="row g-2 mb-2">
        <div class="col-md-11">
            <input type="text" class="form-control" wire:model="meeting.attendees.{{ $index }}.employee_name" placeholder="Employee Name">
            @error('meeting.attendees.' . $index . '.employee_name') <span class="text-danger small">{{ $message }}</span> @enderror
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-outline-danger w-100" wire:click="removeAttendee({{ $index }})">X</button>
        </div>
    </div>
@endforeach
<hr>
<div class="d-flex justify-content-between align-items-center mb-2">
    <h6 class="mb-0">Meeting Agenda</h6>
    <button type="button" class="btn btn-sm btn-outline-primary" wire:click="addAgendaItem">Add Agenda</button>
</div>
@foreach ($meeting['agenda_items'] as $index => $agenda)
    <div class="border rounded p-3 mb-3">
        <div class="row g-2">
            <div class="col-md-5">
                <label class="form-label">Agenda Point</label>
                <input type="text" class="form-control" wire:model="meeting.agenda_items.{{ $index }}.agenda_point">
            </div>
            <div class="col-md-2">
                <label class="form-label">Department</label>
                <input type="text" class="form-control" wire:model="meeting.agenda_items.{{ $index }}.department">
            </div>
            <div class="col-md-2">
                <label class="form-label">Priority</label>
                <select class="form-select" wire:model="meeting.agenda_items.{{ $index }}.priority">
                    @foreach ($priorityOptions as $priority)
                        <option value="{{ $priority }}">{{ $priority }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Attendance Status</label>
                <select class="form-select" wire:model="meeting.agenda_items.{{ $index }}.attendance_status">
                    @foreach ($attendanceStatuses as $status)
                        <option value="{{ $status }}">{{ $status }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="button" class="btn btn-outline-danger w-100" wire:click="removeAgendaItem({{ $index }})">X</button>
            </div>
            <div class="col-md-12">
                <label class="form-label">Responsible Person for Next Task</label>
                <input type="text" class="form-control" wire:model="meeting.agenda_items.{{ $index }}.responsible_person">
            </div>
        </div>
    </div>
@endforeach
<hr>
<div class="mb-3">
    <label class="form-label">Meeting Summary / Report</label>
    <textarea class="form-control" rows="4" wire:model="meeting.discussion_point" placeholder="Discussion Point"></textarea>
</div>
<hr>
<div class="d-flex justify-content-between align-items-center mb-2">
    <h6 class="mb-0">Followup Action Items / Task Assignment</h6>
    <button type="button" class="btn btn-sm btn-outline-primary" wire:click="addActionItem">Add Task</button>
</div>
@foreach ($meeting['action_items'] as $index => $actionItem)
    <div class="border rounded p-3 mb-3">
        <div class="row g-2">
            <div class="col-md-4">
                <label class="form-label">Task</label>
                <input type="text" class="form-control" wire:model="meeting.action_items.{{ $index }}.task">
            </div>
            <div class="col-md-3">
                <label class="form-label">Assigned To</label>
                <input type="text" class="form-control" wire:model="meeting.action_items.{{ $index }}.assigned_to">
            </div>
            <div class="col-md-2">
                <label class="form-label">Deadline</label>
                <input type="date" class="form-control" wire:model="meeting.action_items.{{ $index }}.deadline">
            </div>
            <div class="col-md-2">
                <label class="form-label">Status</label>
                <select class="form-select" wire:model="meeting.action_items.{{ $index }}.status">
                    @foreach ($taskStatuses as $status)
                        <option value="{{ $status }}">{{ $status }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="button" class="btn btn-outline-danger w-100" wire:click="removeActionItem({{ $index }})">X</button>
            </div>
            <div class="col-md-4">
                <label class="form-label">Summary</label>
                <input type="text" class="form-control" wire:model="meeting.action_items.{{ $index }}.summary">
            </div>
            <div class="col-md-6">
                <label class="form-label">Details</label>
                <input type="text" class="form-control" wire:model="meeting.action_items.{{ $index }}.details">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <div class="form-check mt-4">
                    <input class="form-check-input" type="checkbox" wire:model="meeting.action_items.{{ $index }}.uploaded" id="uploaded{{ $index }}">
                    <label class="form-check-label" for="uploaded{{ $index }}">Uploaded</label>
                </div>
            </div>
        </div>
    </div>
@endforeach
<hr>
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Next Follow-Up Date</label>
        <input type="date" class="form-control" wire:model="meeting.next_follow_up_date">
    </div>
    <div class="col-md-6">
        <label class="form-label">Follow-Up Action Summary</label>
        <input type="text" class="form-control" wire:model="meeting.followup_action">
    </div>
    <div class="col-md-12">
        <label class="form-label">Next Follow-Up Details</label>
        <textarea class="form-control" rows="3" wire:model="meeting.next_follow_up_details"></textarea>
    </div>
</div>
<hr>
<h6>Attachments Section</h6>
<div class="table-responsive">
    <table class="table table-bordered align-middle">
        <thead>
            <tr>
                <th>Attachment Type</th>
                <th>Uploaded</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($meeting['attachments'] as $index => $attachment)
                <tr>
                    <td>{{ $attachment['attachment_type'] }}</td>
                    <td>
                        <input type="file" class="form-control" wire:model="attachmentUploads.{{ $index }}">
                        @if (!empty($attachment['file_path']))
                            <div class="mt-2 small">
                                <a href="{{ Storage::url($attachment['file_path']) }}" target="_blank">
                                    {{ $attachment['original_name'] ?: 'View attachment' }}
                                </a>
                            </div>
                        @endif
                        <div class="mt-1 small text-muted">
                            Status: {{ !empty($attachment['file_path']) ? 'Uploaded' : 'Pending' }}
                        </div>
                        @error('attachmentUploads.' . $index) <span class="text-danger small">{{ $message }}</span> @enderror
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
