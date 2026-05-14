<?php

namespace App\Livewire\Meetings;

use App\Models\Client;
use App\Models\Lead;
use App\Models\MeetingSummary;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class MeetingSummaryManagement extends Component
{
    use WithFileUploads;
    use WithPagination;

    public $search = '';
    public $showForm = false;
    public $editingMeetingId = null;
    public $meeting = [];
    public $clients = [];
    public $leads = [];
    public $employees = [];
    public $partyOptions = [];
    public $selectedParty = '';
    public bool $showLeadOptions = false;
    public $attachmentUploads = [];

    // Tracks which view to render — set once in mount(), never touched in render()
    public string $pageMode = 'list'; // 'list' | 'create' | 'edit' | 'preview' | 'print'

    public array $meetingTypes = [
        'Employee with Employee',
        'Employee with Client',
        'Client Meeting',
        'Internal Review',
    ];

    public array $meetingModes = ['Online', 'Offline'];
    public array $departments = ['HR', 'Sales', 'Legal', 'Accounts', 'Operations'];
    public array $priorityOptions = ['Low', 'Medium', 'High'];
    public array $attendanceStatuses = ['Present', 'Absent', 'Pending'];
    public array $taskStatuses = ['Pending', 'In Progress', 'Completed', 'Blocked'];

    public function mount(): void
    {
        $this->clients = Client::orderBy('client_name')
            ->get(['id', 'client_name', 'phone_number'])
            ->map(fn (Client $client) => [
                'id' => $client->id,
                'client_name' => $client->client_name,
                'phone_number' => $client->phone_number,
            ])
            ->toArray();

        $this->leads = Lead::orderBy('name')
            ->get(['id', 'name', 'phone', 'company'])
            ->map(fn (Lead $lead) => [
                'id' => $lead->id,
                'name' => $lead->name,
                'phone' => $lead->phone,
                'company' => $lead->company,
            ])
            ->toArray();

        $this->employees = User::with('roles')
            ->orderBy('name')
            ->get(['id', 'name'])
            ->map(fn (User $employee) => [
                'id' => $employee->id,
                'name' => $employee->name,
                'is_sales' => $employee->hasRole('Sales'),
            ])
            ->toArray();

        $this->resetMeeting();

        // Detect route once here — render() must NOT do this, as it runs on every
        // Livewire re-render (including wire:click), which would reset component state.
        $route     = request()->route();
        $routeName = $route ? $route->getName() : null;

        if ($routeName === 'meetings.summary.create') {
            $this->pageMode          = 'create';
            $this->editingMeetingId  = null;

        } elseif ($routeName === 'meetings.summary.edit') {
            $this->pageMode = 'edit';
            $meetingId      = (int) $route->parameter('meeting');
            $this->editMeeting($meetingId);

        } elseif ($routeName === 'meetings.summary.preview') {
            $this->pageMode         = 'preview';
            $this->editingMeetingId = (int) $route->parameter('meeting');

        } elseif ($routeName === 'meetings.summary.print') {
            $this->pageMode         = 'print';
            $this->editingMeetingId = (int) $route->parameter('meeting');

        } else {
            $this->pageMode = 'list';
        }
    }

    public function render()
    {
        // Return the correct view based on the mode set in mount().
        // No route detection here — that would re-run on every wire:click and
        // destroy unsaved state (the root cause of Add Attendee/Agenda/Task not working).
        if ($this->pageMode === 'create' || $this->pageMode === 'edit') {
            return view('livewire.meetings.meeting-summary-form', [
                'meetingId' => $this->editingMeetingId,
            ]);
        }

        if ($this->pageMode === 'preview') {
            return view('livewire.meetings.meeting-summary-preview', [
                'meetingRecord' => MeetingSummary::with(['client', 'creator'])->findOrFail($this->editingMeetingId),
            ]);
        }

        if ($this->pageMode === 'print') {
            return view('livewire.meetings.meeting-summary-print', [
                'meetingRecord' => MeetingSummary::with(['client', 'creator'])->findOrFail($this->editingMeetingId),
            ]);
        }

        // List page
        $meetings = MeetingSummary::with(['client', 'creator'])
            ->when($this->search, function ($query) {
                $query->where(function ($innerQuery) {
                    $innerQuery->where('meeting_type', 'like', '%' . $this->search . '%')
                        ->orWhere('client_name', 'like', '%' . $this->search . '%')
                        ->orWhere('company_name', 'like', '%' . $this->search . '%')
                        ->orWhere('department', 'like', '%' . $this->search . '%')
                        ->orWhere('meeting_location', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()
            ->paginate(10);

        return view('livewire.meetings.meeting-summary-management', [
            'meetings' => $meetings,
        ]);
    }

    protected function rules(): array
    {
        return [
            'meeting.meeting_type'                          => 'required|string|max:255',
            'meeting.meeting_mode'                          => 'nullable|string|max:50',
            'meeting.meeting_date'                          => 'required|date',
            'meeting.start_time'                            => 'nullable',
            'meeting.end_time'                              => 'nullable',
            'meeting.meeting_location'                      => 'nullable|string|max:255',
            'meeting.meeting_created_by'                    => 'nullable|exists:users,id',
            'meeting.department'                            => 'nullable|string|max:255',
            'meeting.meeting_attended'                      => 'nullable|string|max:255',
            'meeting.client_id'                             => 'nullable|exists:clients,id',
            'meeting.client_name'                           => 'nullable|string|max:255',
            'meeting.company_name'                          => 'nullable|string|max:255',
            'meeting.contact_number'                        => 'nullable|string|max:50',
            'meeting.contact_person'                        => 'nullable|string|max:255',
            'meeting.discussion_point'                      => 'nullable|string',
            'meeting.followup_action'                       => 'nullable|string',
            'meeting.next_follow_up_date'                   => 'nullable|date',
            'meeting.next_follow_up_details'                => 'nullable|string',
            'meeting.attendees'                             => 'required|array|min:1',
            'meeting.attendees.*.employee_name'             => 'required|string|max:255',
            'meeting.agenda_items'                          => 'required|array|min:1',
            'meeting.agenda_items.*.agenda_point'           => 'required|string|max:255',
            'meeting.agenda_items.*.department'             => 'nullable|string|max:255',
            'meeting.agenda_items.*.priority'               => 'nullable|string|max:50',
            'meeting.agenda_items.*.attendance_status'      => 'nullable|string|max:50',
            'meeting.agenda_items.*.responsible_person'     => 'nullable|string|max:255',
            'meeting.action_items'                          => 'required|array|min:1',
            'meeting.action_items.*.task'                   => 'required|string|max:255',
            'meeting.action_items.*.assigned_to'            => 'nullable|string|max:255',
            'meeting.action_items.*.summary'                => 'nullable|string|max:255',
            'meeting.action_items.*.deadline'               => 'nullable|date',
            'meeting.action_items.*.details'                => 'nullable|string',
            'meeting.action_items.*.status'                 => 'nullable|string|max:50',
            'meeting.action_items.*.uploaded'               => 'boolean',
            'meeting.attachments'                           => 'required|array|min:1',
            'meeting.attachments.*.attachment_type'         => 'required|string|max:255',
            'meeting.attachments.*.uploaded'                => 'boolean',
            'meeting.attachments.*.file_path'               => 'nullable|string|max:255',
            'meeting.attachments.*.original_name'           => 'nullable|string|max:255',
            'attachmentUploads.*'                           => 'nullable|file|max:10240',
        ];
    }

    public function showCreateForm(): void
    {
        $this->resetMeeting();
        $this->showForm = true;
    }

    public function save(): void
    {
        $validated = $this->validate()['meeting'];
        $validated = $this->normalizeNullableFields($validated);

        if (empty($validated['meeting_created_by'])) {
            $validated['meeting_created_by'] = Auth::id();
        }

        if (! empty($validated['client_id']) && empty($validated['client_name'])) {
            $client = Client::find($validated['client_id']);
            if ($client) {
                $validated['client_name']    = $client->client_name;
                $validated['contact_number'] = $validated['contact_number'] ?: $client->phone_number;
            }
        }

        $validated['attendees']    = array_values($validated['attendees']);
        $validated['agenda_items'] = array_values($validated['agenda_items']);
        $validated['action_items'] = array_values($validated['action_items']);
        $validated['attachments']  = $this->prepareAttachments(array_values($validated['attachments']));

        MeetingSummary::updateOrCreate(
            ['id' => $this->editingMeetingId],
            $validated
        );

        $message = $this->editingMeetingId
            ? 'Meeting summary updated successfully.'
            : 'Meeting summary created successfully.';

        $this->closeForm();
        session()->flash('success', $message);
        $this->resetPage();
    }

    public function editMeeting(int $meetingId): void
    {
        $meeting                 = MeetingSummary::findOrFail($meetingId);
        $this->editingMeetingId  = $meetingId;
        $this->meeting           = array_merge($this->defaultMeeting(), Arr::only($meeting->toArray(), [
            'client_id',
            'meeting_created_by',
            'meeting_type',
            'meeting_mode',
            'meeting_date',
            'start_time',
            'end_time',
            'meeting_location',
            'department',
            'meeting_attended',
            'client_name',
            'company_name',
            'contact_number',
            'contact_person',
            'discussion_point',
            'followup_action',
            'next_follow_up_date',
            'next_follow_up_details',
            'attendees',
            'agenda_items',
            'action_items',
            'attachments',
        ]));

        $this->meeting['meeting_date']        = optional($meeting->meeting_date)->format('Y-m-d');
        $this->meeting['next_follow_up_date'] = optional($meeting->next_follow_up_date)->format('Y-m-d');
        $this->meeting['attendees']           = $this->normalizeRows($this->meeting['attendees'] ?? [], 'attendee');
        $this->meeting['agenda_items']        = $this->normalizeRows($this->meeting['agenda_items'] ?? [], 'agenda');
        $this->meeting['action_items']        = $this->normalizeRows($this->meeting['action_items'] ?? [], 'action');
        $this->meeting['attachments']         = $this->normalizeRows($this->meeting['attachments'] ?? [], 'attachment');
        $this->attachmentUploads              = [];
        $this->refreshPartyOptions();
        $this->syncSelectedPartyFromMeeting();
        $this->showForm                       = true;
    }

    public function deleteMeeting(int $meetingId): void
    {
        $meeting = MeetingSummary::find($meetingId);

        if ($meeting) {
            $this->deleteStoredAttachments($meeting->attachments ?? []);
            $meeting->delete();
            session()->flash('success', 'Meeting summary deleted successfully.');
            $this->resetPage();
        }
    }

    public function closeForm(): void
    {
        $this->resetValidation();
        $this->editingMeetingId = null;
        $this->showForm         = false;
        $this->attachmentUploads = [];
        $this->resetMeeting();
    }

    public function addAttendee(): void
    {
        $this->meeting['attendees'][] = $this->defaultAttendee();
    }

    public function removeAttendee(int $index): void
    {
        unset($this->meeting['attendees'][$index]);
        $this->meeting['attendees'] = $this->normalizeRows($this->meeting['attendees'], 'attendee');
    }

    public function addAgendaItem(): void
    {
        $this->meeting['agenda_items'][] = $this->defaultAgendaItem();
    }

    public function removeAgendaItem(int $index): void
    {
        unset($this->meeting['agenda_items'][$index]);
        $this->meeting['agenda_items'] = $this->normalizeRows($this->meeting['agenda_items'], 'agenda');
    }

    public function addActionItem(): void
    {
        $this->meeting['action_items'][] = $this->defaultActionItem();
    }

    public function removeActionItem(int $index): void
    {
        unset($this->meeting['action_items'][$index]);
        $this->meeting['action_items'] = $this->normalizeRows($this->meeting['action_items'], 'action');
    }

    public function updatedMeetingClientId($value): void
    {
        if (! $value) {
            $this->selectedParty = '';
            return;
        }

        $this->selectedParty = 'client:' . $value;
        $this->hydrateMeetingParty($this->selectedParty);
    }

    public function updatedMeetingMeetingCreatedBy($value): void
    {
        if (empty($value)) {
            $this->meeting['meeting_created_by'] = Auth::id();
        }

        $this->refreshPartyOptions();

        if (! empty($this->selectedParty) && ! $this->selectedPartyExists($this->selectedParty)) {
            $this->selectedParty = '';
            $this->clearSelectedPartyFields();
        }
    }

    public function updatedSelectedParty($value): void
    {
        $this->hydrateMeetingParty($value);
    }

    protected function resetMeeting(): void
    {
        $this->meeting           = $this->defaultMeeting();
        $this->refreshPartyOptions();
        $this->syncSelectedPartyFromMeeting();
        $this->attachmentUploads = [];
    }

    protected function defaultMeeting(): array
    {
        return [
            'client_id'              => '',
            'meeting_created_by'     => Auth::id(),
            'meeting_type'           => 'Employee with Client',
            'meeting_mode'           => 'Offline',
            'meeting_date'           => now()->format('Y-m-d'),
            'start_time'             => '',
            'end_time'               => '',
            'meeting_location'       => '',
            'department'             => 'Sales',
            'meeting_attended'       => 'Employee with Client',
            'client_name'            => '',
            'company_name'           => '',
            'contact_number'         => '',
            'contact_person'         => '',
            'discussion_point'       => '',
            'followup_action'        => '',
            'next_follow_up_date'    => '',
            'next_follow_up_details' => '',
            'attendees'              => [$this->defaultAttendee()],
            'agenda_items'           => [$this->defaultAgendaItem()],
            'action_items'           => [$this->defaultActionItem()],
            'attachments'            => $this->defaultAttachments(),
        ];
    }

    protected function defaultAttendee(): array
    {
        return ['employee_name' => ''];
    }

    protected function defaultAgendaItem(): array
    {
        return [
            'agenda_point'       => '',
            'department'         => '',
            'priority'           => 'Medium',
            'attendance_status'  => 'Present',
            'responsible_person' => '',
        ];
    }

    protected function defaultActionItem(): array
    {
        return [
            'task'        => '',
            'assigned_to' => '',
            'summary'     => '',
            'deadline'    => '',
            'details'     => '',
            'status'      => 'Pending',
            'uploaded'    => false,
        ];
    }

    protected function defaultAttachments(): array
    {
        return [
            ['attachment_type' => 'Meeting Recording',      'uploaded' => false, 'file_path' => null, 'original_name' => null],
            ['attachment_type' => 'Screenshot',             'uploaded' => false, 'file_path' => null, 'original_name' => null],
            ['attachment_type' => 'Signed Documents',       'uploaded' => false, 'file_path' => null, 'original_name' => null],
            ['attachment_type' => 'Proposal Copy',          'uploaded' => false, 'file_path' => null, 'original_name' => null],
            ['attachment_type' => 'MOM + Recording Attach', 'uploaded' => false, 'file_path' => null, 'original_name' => null],
        ];
    }

    protected function prepareAttachments(array $attachments): array
    {
        foreach ($attachments as $index => $attachment) {
            $existingPath = $attachment['file_path'] ?? null;
            $upload       = $this->attachmentUploads[$index] ?? null;

            if ($upload) {
                if ($existingPath) {
                    Storage::disk('public')->delete($existingPath);
                }

                $storedPath = $upload->store('meeting-attachments', 'public');

                $attachments[$index]['file_path']      = $storedPath;
                $attachments[$index]['original_name']  = $upload->getClientOriginalName();
                $attachments[$index]['uploaded']       = true;
                continue;
            }

            $attachments[$index]['uploaded']      = ! empty($existingPath);
            $attachments[$index]['original_name'] = $attachment['original_name'] ?? null;
            $attachments[$index]['file_path']     = $existingPath;
        }

        $this->attachmentUploads = [];

        return $attachments;
    }

    protected function normalizeNullableFields(array $validated): array
    {
        foreach ([
            'client_id',
            'meeting_created_by',
            'meeting_mode',
            'start_time',
            'end_time',
            'meeting_location',
            'department',
            'meeting_attended',
            'client_name',
            'company_name',
            'contact_number',
            'contact_person',
            'discussion_point',
            'followup_action',
            'next_follow_up_date',
            'next_follow_up_details',
        ] as $field) {
            if (array_key_exists($field, $validated) && $validated[$field] === '') {
                $validated[$field] = null;
            }
        }

        return $validated;
    }

    protected function deleteStoredAttachments(array $attachments): void
    {
        foreach ($attachments as $attachment) {
            if (! empty($attachment['file_path'])) {
                Storage::disk('public')->delete($attachment['file_path']);
            }
        }
    }

    protected function normalizeRows(array $rows, string $type): array
    {
        $rows = array_values(array_filter($rows, fn ($row) => is_array($row)));

        if ($type === 'attachment') {
            $rows = array_map(function (array $row) {
                return array_merge([
                    'attachment_type' => '',
                    'uploaded'        => false,
                    'file_path'       => null,
                    'original_name'   => null,
                ], $row, [
                    'uploaded' => ! empty($row['file_path']) || ! empty($row['uploaded']),
                ]);
            }, $rows);
        }

        if (! empty($rows)) {
            return $rows;
        }

        return match ($type) {
            'attendee'   => [$this->defaultAttendee()],
            'agenda'     => [$this->defaultAgendaItem()],
            'action'     => [$this->defaultActionItem()],
            'attachment' => $this->defaultAttachments(),
            default      => [],
        };
    }

    protected function refreshPartyOptions(): void
    {
        $this->showLeadOptions = $this->selectedEmployeeCanChooseLeadOrClient();

        $clientOptions = array_map(function (array $client) {
            return [
                'value' => 'client:' . $client['id'],
                'label' => $client['client_name'] . ' (Client)',
            ];
        }, $this->clients);

        $leadOptions = [];

        if ($this->showLeadOptions) {
            $leadOptions = array_map(function (array $lead) {
                $company = ! empty($lead['company']) ? ' - ' . $lead['company'] : '';

                return [
                    'value' => 'lead:' . $lead['id'],
                    'label' => $lead['name'] . $company . ' (Lead)',
                ];
            }, $this->leads);
        }

        $this->partyOptions = array_merge($clientOptions, $leadOptions);
    }

    protected function selectedEmployeeCanChooseLeadOrClient(): bool
    {
        $employeeId = (int) ($this->meeting['meeting_created_by'] ?: Auth::id());

        if (! $employeeId) {
            return false;
        }

        $employee = User::find($employeeId);

        return (bool) ($employee?->hasAnyRole(['Sales', 'Super Admin']));
    }

    protected function syncSelectedPartyFromMeeting(): void
    {
        $clientId = $this->meeting['client_id'] ?? '';

        if (! empty($clientId)) {
            $this->selectedParty = 'client:' . $clientId;
            return;
        }

        $this->selectedParty = '';
    }

    protected function selectedPartyExists(string $value): bool
    {
        return collect($this->partyOptions)->contains(fn (array $option) => $option['value'] === $value);
    }

    protected function hydrateMeetingParty($value): void
    {
        if (empty($value)) {
            $this->clearSelectedPartyFields();
            return;
        }

        [$type, $id] = $this->parsePartyValue($value);

        if (! $type || ! $id) {
            return;
        }

        if ($type === 'client') {
            $client = collect($this->clients)->firstWhere('id', $id);

            if (! $client) {
                return;
            }

            $this->meeting['client_id'] = $client['id'];
            $this->meeting['client_name'] = $client['client_name'] ?? '';
            $this->meeting['company_name'] = $client['client_name'] ?? '';
            $this->meeting['contact_number'] = $client['phone_number'] ?? '';
            $this->meeting['contact_person'] = $client['client_name'] ?? '';

            return;
        }

        if ($type === 'lead' && $this->showLeadOptions) {
            $lead = collect($this->leads)->firstWhere('id', $id);

            if (! $lead) {
                return;
            }

            $this->meeting['client_id'] = '';
            $this->meeting['client_name'] = $lead['name'] ?? '';
            $this->meeting['company_name'] = $lead['company'] ?? '';
            $this->meeting['contact_number'] = $lead['phone'] ?? '';
            $this->meeting['contact_person'] = $lead['name'] ?? '';
        }
    }

    protected function clearSelectedPartyFields(): void
    {
        $this->meeting['client_id'] = '';
        $this->meeting['client_name'] = '';
        $this->meeting['company_name'] = '';
        $this->meeting['contact_number'] = '';
        $this->meeting['contact_person'] = '';
    }

    protected function parsePartyValue(string $value): array
    {
        if (! str_contains($value, ':')) {
            return [null, null];
        }

        [$type, $id] = explode(':', $value, 2);

        return [$type, (int) $id];
    }
}
