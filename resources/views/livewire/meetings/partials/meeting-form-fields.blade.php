{{-- This partial contains all meeting form fields, extracted for reuse in the standalone form --}}
<div class="d-flex flex-column gap-4">
    <section class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom py-3">
            <div class="d-flex align-items-center justify-content-between gap-3">
                <div>
                    <div class="text-uppercase small fw-semibold text-primary mb-1">Section 01</div>
                    <h6 class="mb-1 fw-semibold">Meeting Details</h6>
                    <p class="text-muted small mb-0">Core schedule, ownership, and venue information.</p>
                </div>
                <span class="badge bg-light text-dark border">Required Details</span>
            </div>
        </div>
        <div class="card-body p-3 p-md-4">
            <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label small text-uppercase fw-semibold text-secondary mb-1">Meeting Type</label>
                <select class="form-select" wire:model="meeting.meeting_type">
                    @foreach ($meetingTypes as $type)
                        <option value="{{ $type }}">{{ $type }}</option>
                    @endforeach
                </select>
                @error('meeting.meeting_type') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>
            <div class="col-md-4">
                <label class="form-label small text-uppercase fw-semibold text-secondary mb-1">Meeting Mode</label>
                <select class="form-select" wire:model="meeting.meeting_mode">
                    @foreach ($meetingModes as $mode)
                        <option value="{{ $mode }}">{{ $mode }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small text-uppercase fw-semibold text-secondary mb-1">Meeting Date</label>
                <input type="date" class="form-control" wire:model="meeting.meeting_date">
                @error('meeting.meeting_date') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <div class="col-md-3">
                <label class="form-label small text-uppercase fw-semibold text-secondary mb-1">Start Time</label>
                <input type="time" class="form-control" wire:model="meeting.start_time">
            </div>
            <div class="col-md-3">
                <label class="form-label small text-uppercase fw-semibold text-secondary mb-1">End Time</label>
                <input type="time" class="form-control" wire:model="meeting.end_time">
            </div>
            <div class="col-md-3">
                <label class="form-label small text-uppercase fw-semibold text-secondary mb-1">Department</label>
                <select class="form-select" wire:model="meeting.department">
                    @foreach ($departments as $department)
                        <option value="{{ $department }}">{{ $department }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small text-uppercase fw-semibold text-secondary mb-1">Created By</label>
                <select class="form-select" wire:model="meeting.meeting_created_by">
                    <option value="">Select Employee</option>
                    @foreach ($employees as $employee)
                        <option value="{{ $employee['id'] }}">{{ $employee['name'] }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label small text-uppercase fw-semibold text-secondary mb-1">Meeting Location</label>
                <input type="text" class="form-control" wire:model="meeting.meeting_location" placeholder="Office, client office, Google Meet, Zoom">
            </div>
            <div class="col-md-6">
                <label class="form-label small text-uppercase fw-semibold text-secondary mb-1">Meeting Attended</label>
                <input type="text" class="form-control" wire:model="meeting.meeting_attended" placeholder="Employee with employee or client">
            </div>
        </div>
        </div>
    </section>

    <section class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom py-3">
            <div class="d-flex align-items-center justify-content-between gap-3">
                <div>
                    <div class="text-uppercase small fw-semibold text-primary mb-1">Section 02</div>
                    <h6 class="mb-1 fw-semibold">Client Details</h6>
                    <p class="text-muted small mb-0">Commercial and contact information for the meeting record.</p>
                </div>
                <span class="badge bg-light text-dark border">Client Profile</span>
            </div>
        </div>
        <div class="card-body p-3 p-md-4">
            <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label small text-uppercase fw-semibold text-secondary mb-1">
                    {{ $showLeadOptions ? 'Lead / Client' : 'Client' }}
                </label>
                <select class="form-select" wire:model.live="selectedParty">
                    <option value="">Select {{ $showLeadOptions ? 'Lead or Client' : 'Client' }}</option>
                    @foreach ($partyOptions as $partyOption)
                        <option value="{{ $partyOption['value'] }}">{{ $partyOption['label'] }}</option>
                    @endforeach
                </select>
                <div class="form-text small">
                    {{ $showLeadOptions ? 'Sales and Super Admin users can select either an existing lead or an existing client.' : 'Only client records are available for the selected employee.' }}
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-label small text-uppercase fw-semibold text-secondary mb-1">Client Name</label>
                <input type="text" class="form-control" wire:model="meeting.client_name">
            </div>
            <div class="col-md-4">
                <label class="form-label small text-uppercase fw-semibold text-secondary mb-1">Company Name</label>
                <input type="text" class="form-control" wire:model="meeting.company_name">
            </div>
            <div class="col-md-6">
                <label class="form-label small text-uppercase fw-semibold text-secondary mb-1">Contact Number</label>
                <input type="text" class="form-control" wire:model="meeting.contact_number">
            </div>
            <div class="col-md-6">
                <label class="form-label small text-uppercase fw-semibold text-secondary mb-1">Client / Employee Contact</label>
                <input type="text" class="form-control" wire:model="meeting.contact_person">
            </div>
        </div>
        </div>
    </section>

    <section class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom py-3">
            <div class="align-items-center justify-content-between gap-3 flex-wrap">
                <div>
                    <div class="text-uppercase small fw-semibold text-primary mb-1">Section 03</div>
                    <h6 class="mb-1 fw-semibold">Attendees</h6>
                    <p class="text-muted small mb-0">List everyone present in the meeting.</p>
                </div>
                <button type="button" class="btn btn-primary btn-sm px-3" wire:click="addAttendee">Add Attendee</button>
            </div>
        </div>
        <div class="card-body p-3 p-md-4">
            <div class="d-flex flex-column gap-3">
            @foreach ($meeting['attendees'] as $index => $attendee)
                @php $attendeeKey = isset($attendee['id']) ? $attendee['id'] : $index; @endphp
                <div class="border rounded-3 p-3 bg-light" wire:key="attendee-{{ $attendeeKey }}">
                    <div class="row g-3 align-items-end">
                    <div class="col-md-11">
                        <label class="form-label small text-uppercase fw-semibold text-secondary mb-1">Attendee {{ $index + 1 }}</label>
                        <input type="text" class="form-control" wire:model="meeting.attendees.{{ $index }}.employee_name" placeholder="Employee name">
                        @error('meeting.attendees.' . $index . '.employee_name') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-outline-danger btn-sm" wire:click="removeAttendee({{ $index }})">Remove Attendee</button>
                    </div>
                    </div>
                </div>
            @endforeach
        </div>
        </div>
    </section>

    <section class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom py-3">
            <div class="align-items-center justify-content-between gap-3 flex-wrap">
                <div>
                    <div class="text-uppercase small fw-semibold text-primary mb-1">Section 04</div>
                    <h6 class="mb-1 fw-semibold">Meeting Agenda</h6>
                    <p class="text-muted small mb-0">Capture discussion points, ownership, and attendance status.</p>
                </div>
                <button type="button" class="btn btn-primary btn-sm px-3" wire:click="addAgendaItem">Add Agenda</button>
            </div>
        </div>
        <div class="card-body p-3 p-md-4">
            <div class="d-flex flex-column gap-3">
            @foreach ($meeting['agenda_items'] as $index => $agenda)
                @php $agendaKey = isset($agenda['id']) ? $agenda['id'] : $index; @endphp
                <div class="card border bg-light" wire:key="agenda-{{ $agendaKey }}">
                    <div class="card-body p-3">
                    <div class="justify-content-between align-items-center mb-3 gap-3">
                        <span class="small fw-semibold text-uppercase text-muted">Agenda Item {{ $index + 1 }}</span>
                        <button type="button" class="btn btn-outline-danger btn-sm" wire:click="removeAgendaItem({{ $index }})">Remove</button>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="form-label small text-uppercase fw-semibold text-secondary mb-1">Agenda Point</label>
                            <input type="text" class="form-control" wire:model="meeting.agenda_items.{{ $index }}.agenda_point">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small text-uppercase fw-semibold text-secondary mb-1">Department</label>
                            <input type="text" class="form-control" wire:model="meeting.agenda_items.{{ $index }}.department">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small text-uppercase fw-semibold text-secondary mb-1">Priority</label>
                            <select class="form-select" wire:model="meeting.agenda_items.{{ $index }}.priority">
                                @foreach ($priorityOptions as $priority)
                                    <option value="{{ $priority }}">{{ $priority }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small text-uppercase fw-semibold text-secondary mb-1">Attendance Status</label>
                            <select class="form-select" wire:model="meeting.agenda_items.{{ $index }}.attendance_status">
                                @foreach ($attendanceStatuses as $status)
                                    <option value="{{ $status }}">{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small text-uppercase fw-semibold text-secondary mb-1">Responsible Person for Next Task</label>
                            <input type="text" class="form-control" wire:model="meeting.agenda_items.{{ $index }}.responsible_person">
                        </div>
                    </div>
                    </div>
                </div>
            @endforeach
        </div>
        </div>
    </section>

    <section class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom py-3">
            <div class="text-uppercase small fw-semibold text-primary mb-1">Section 05</div>
            <h6 class="mb-1 fw-semibold">Meeting Summary</h6>
            <p class="text-muted small mb-0">Write a concise summary of discussion and outcomes.</p>
        </div>
        <div class="card-body p-3 p-md-4">
            <textarea class="form-control" rows="4" wire:model="meeting.discussion_point" placeholder="Discussion points, decisions, and context"></textarea>
        </div>
    </section>

    <section class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom py-3">
            <div class="align-items-center justify-content-between gap-3 flex-wrap">
                <div>
                    <div class="text-uppercase small fw-semibold text-primary mb-1">Section 06</div>
                    <h6 class="mb-1 fw-semibold">Action Items</h6>
                    <p class="text-muted small mb-0">Track assignments, deadlines, and completion status.</p>
                </div>
                <button type="button" class="btn btn-primary btn-sm px-3" wire:click="addActionItem">Add Task</button>
            </div>
        </div>
        <div class="card-body p-3 p-md-4">
            <div class=" flex-column gap-3">
            @foreach ($meeting['action_items'] as $index => $actionItem)
                @php $actionKey = isset($actionItem['id']) ? $actionItem['id'] : $index; @endphp
                <div class="card border bg-light" wire:key="action-{{ $actionKey }}">
                    <div class="card-body p-3">
                    <div class="justify-content-between align-items-center mb-3 gap-3">
                        <span class="small fw-semibold text-uppercase text-muted">Task Item {{ $index + 1 }}</span>
                        <button type="button" class="btn btn-outline-danger btn-sm" wire:click="removeActionItem({{ $index }})">Remove</button>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small text-uppercase fw-semibold text-secondary mb-1">Task</label>
                            <input type="text" class="form-control" wire:model="meeting.action_items.{{ $index }}.task">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small text-uppercase fw-semibold text-secondary mb-1">Assigned To</label>
                            <input type="text" class="form-control" wire:model="meeting.action_items.{{ $index }}.assigned_to">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small text-uppercase fw-semibold text-secondary mb-1">Deadline</label>
                            <input type="date" class="form-control" wire:model="meeting.action_items.{{ $index }}.deadline">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small text-uppercase fw-semibold text-secondary mb-1">Status</label>
                            <select class="form-select" wire:model="meeting.action_items.{{ $index }}.status">
                                @foreach ($taskStatuses as $status)
                                    <option value="{{ $status }}">{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-uppercase fw-semibold text-secondary mb-1">Summary</label>
                            <input type="text" class="form-control" wire:model="meeting.action_items.{{ $index }}.summary">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label small text-uppercase fw-semibold text-secondary mb-1">Details</label>
                            <input type="text" class="form-control" wire:model="meeting.action_items.{{ $index }}.details">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" wire:model="meeting.action_items.{{ $index }}.uploaded" id="uploaded{{ $index }}">
                                <label class="form-check-label small fw-semibold text-secondary" for="uploaded{{ $index }}">Marked as uploaded</label>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            @endforeach
        </div>
        </div>
    </section>

    <section class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom py-3">
            <div class="d-flex align-items-center justify-content-between gap-3">
                <div>
                    <div class="text-uppercase small fw-semibold text-primary mb-1">Section 07</div>
                    <h6 class="mb-1 fw-semibold">Follow-Up</h6>
                    <p class="text-muted small mb-0">Document next steps and planned follow-up communication.</p>
                </div>
                <span class="badge bg-light text-dark border">Next Steps</span>
            </div>
        </div>
        <div class="card-body p-3 p-md-4">
            <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label small text-uppercase fw-semibold text-secondary mb-1">Next Follow-Up Date</label>
                <input type="date" class="form-control" wire:model="meeting.next_follow_up_date">
            </div>
            <div class="col-md-8">
                <label class="form-label small text-uppercase fw-semibold text-secondary mb-1">Follow-Up Action Summary</label>
                <input type="text" class="form-control" wire:model="meeting.followup_action">
            </div>
            <div class="col-md-12">
                <label class="form-label small text-uppercase fw-semibold text-secondary mb-1">Next Follow-Up Details</label>
                <textarea class="form-control" rows="3" wire:model="meeting.next_follow_up_details"></textarea>
            </div>
        </div>
        </div>
    </section>

    <section class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom py-3">
            <div class="d-flex align-items-center justify-content-between gap-3">
                <div>
                    <div class="text-uppercase small fw-semibold text-primary mb-1">Section 08</div>
                    <h6 class="mb-1 fw-semibold">Attachments</h6>
                    <p class="text-muted small mb-0">Upload supporting documents and track their availability.</p>
                </div>
                <span class="badge bg-light text-dark border">Supporting Files</span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
            <table class="table table-sm table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="fw-semibold">Attachment Type</th>
                        <th class="fw-semibold">Upload</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($meeting['attachments'] as $index => $attachment)
                        @php $attachmentKey = isset($attachment['id']) ? $attachment['id'] : $index; @endphp
                        <tr wire:key="attachment-{{ $attachmentKey }}">
                            <td class="small fw-semibold text-secondary px-3 py-3">{{ $attachment['attachment_type'] }}</td>
                            <td>
                                <div class="p-3">
                                <input type="file" class="form-control form-control-sm" wire:model="attachmentUploads.{{ $index }}">
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
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        </div>
    </section>
</div>
