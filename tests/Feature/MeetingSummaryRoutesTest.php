<?php

use App\Livewire\Meetings\MeetingSummaryManagement;
use App\Models\Client;
use App\Models\Lead;
use App\Models\MeetingSummary;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

it('allows an authenticated user to open the meeting summary page', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('meetings.summary'));

    $response->assertOk()->assertSee('Meeting Summary');
});

it('allows an authenticated user to open the advocate portal page', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('advocate.portal'));

    $response->assertOk()->assertSee('Advocate Portal');
});

it('allows an authenticated user to open the client portal page', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('client.portal'));

    $response->assertOk()->assertSee('Client Portal');
});

it('creates a meeting summary with uploaded attachments', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $client = Client::create([
        'client_name' => 'Acme Client',
        'phone_number' => '9876543210',
        'alternative_number' => null,
        'email' => 'client@example.com',
        'state' => 'West Bengal',
    ]);

    Livewire::actingAs($user)
        ->test(MeetingSummaryManagement::class)
        ->call('showCreateForm')
        ->set('meeting.client_id', $client->id)
        ->set('meeting.meeting_type', 'Employee with Client')
        ->set('meeting.meeting_date', '2026-05-14')
        ->set('meeting.department', 'Sales')
        ->set('meeting.attendees.0.employee_name', 'Rita')
        ->set('meeting.agenda_items.0.agenda_point', 'Initial scope review')
        ->set('meeting.action_items.0.task', 'Send proposal')
        ->set('attachmentUploads.0', UploadedFile::fake()->create('recording.mp4', 120, 'video/mp4'))
        ->call('save')
        ->assertHasNoErrors();

    $meeting = MeetingSummary::first();

    expect($meeting)->not->toBeNull();
    expect($meeting->attachments[0]['file_path'])->not->toBeNull();
    expect($meeting->attachments[0]['uploaded'])->toBeTrue();
    Storage::disk('public')->assertExists($meeting->attachments[0]['file_path']);
});

it('shows leads and clients for sales employees and autofills meeting contact fields', function () {
    Role::findOrCreate('Sales');

    $salesUser = User::factory()->create();
    $salesUser->assignRole('Sales');

    $client = Client::create([
        'client_name' => 'Acme Client',
        'phone_number' => '9876543210',
        'alternative_number' => null,
        'email' => 'client@example.com',
        'state' => 'West Bengal',
    ]);

    $lead = Lead::create([
        'name' => 'Prospect One',
        'phone' => '9988776655',
        'company' => 'Prospect Labs',
        'status' => 'Open',
        'created_by' => $salesUser->id,
    ]);

    $component = Livewire::actingAs($salesUser)
        ->test(MeetingSummaryManagement::class)
        ->set('meeting.meeting_created_by', $salesUser->id);

    expect(collect($component->get('partyOptions'))->pluck('value'))->toContain('client:' . $client->id);
    expect(collect($component->get('partyOptions'))->pluck('value'))->toContain('lead:' . $lead->id);

    $component->set('selectedParty', 'lead:' . $lead->id)
        ->assertSet('meeting.client_id', '')
        ->assertSet('meeting.client_name', 'Prospect One')
        ->assertSet('meeting.company_name', 'Prospect Labs')
        ->assertSet('meeting.contact_number', '9988776655')
        ->assertSet('meeting.contact_person', 'Prospect One');
});

it('shows leads and clients for super admin employees', function () {
    Role::findOrCreate('Super Admin');

    $superAdmin = User::factory()->create();
    $superAdmin->assignRole('Super Admin');

    $client = Client::create([
        'client_name' => 'Admin Client',
        'phone_number' => '9123456789',
        'alternative_number' => null,
        'email' => 'admin-client@example.com',
        'state' => 'West Bengal',
    ]);

    $lead = Lead::create([
        'name' => 'Admin Prospect',
        'phone' => '9234567890',
        'company' => 'Admin Labs',
        'status' => 'Open',
        'created_by' => $superAdmin->id,
    ]);

    $component = Livewire::actingAs($superAdmin)
        ->test(MeetingSummaryManagement::class)
        ->set('meeting.meeting_created_by', $superAdmin->id);

    expect($component->get('showLeadOptions'))->toBeTrue();
    expect(collect($component->get('partyOptions'))->pluck('value'))->toContain('client:' . $client->id);
    expect(collect($component->get('partyOptions'))->pluck('value'))->toContain('lead:' . $lead->id);
});

it('shows only clients for non sales employees', function () {
    $user = User::factory()->create();

    $client = Client::create([
        'client_name' => 'Client Only',
        'phone_number' => '9000000000',
        'alternative_number' => null,
        'email' => 'client-only@example.com',
        'state' => 'West Bengal',
    ]);

    $lead = Lead::create([
        'name' => 'Lead Hidden',
        'phone' => '9111111111',
        'company' => 'Hidden Co',
        'status' => 'Open',
        'created_by' => $user->id,
    ]);

    $component = Livewire::actingAs($user)
        ->test(MeetingSummaryManagement::class)
        ->set('meeting.meeting_created_by', $user->id);

    expect($component->get('showLeadOptions'))->toBeFalse();
    expect(collect($component->get('partyOptions'))->pluck('value'))->toContain('client:' . $client->id);
    expect(collect($component->get('partyOptions'))->pluck('value'))->not->toContain('lead:' . $lead->id);
});

it('updates an existing meeting summary and replaces attachment metadata', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $meeting = MeetingSummary::create([
        'meeting_created_by' => $user->id,
        'meeting_type' => 'Employee with Employee',
        'meeting_mode' => 'Offline',
        'meeting_date' => '2026-05-14',
        'department' => 'HR',
        'meeting_attended' => 'Employee with Employee',
        'attendees' => [['employee_name' => 'Amit']],
        'agenda_items' => [[
            'agenda_point' => 'Internal sync',
            'department' => 'HR',
            'priority' => 'Medium',
            'attendance_status' => 'Present',
            'responsible_person' => 'Amit',
        ]],
        'action_items' => [[
            'task' => 'Share minutes',
            'assigned_to' => 'Amit',
            'summary' => 'Minutes',
            'deadline' => '2026-05-15',
            'details' => 'Email the summary',
            'status' => 'Pending',
            'uploaded' => false,
        ]],
        'attachments' => [[
            'attachment_type' => 'Meeting Recording',
            'uploaded' => false,
            'file_path' => null,
            'original_name' => null,
        ]],
    ]);

    Livewire::actingAs($user)
        ->test(MeetingSummaryManagement::class)
        ->call('editMeeting', $meeting->id)
        ->set('meeting.department', 'Legal')
        ->set('meeting.action_items.0.status', 'Completed')
        ->set('attachmentUploads.0', UploadedFile::fake()->create('minutes.pdf', 50, 'application/pdf'))
        ->call('save')
        ->assertHasNoErrors();

    $meeting->refresh();

    expect($meeting->department)->toBe('Legal');
    expect($meeting->action_items[0]['status'])->toBe('Completed');
    expect($meeting->attachments[0]['original_name'])->toBe('minutes.pdf');
    Storage::disk('public')->assertExists($meeting->attachments[0]['file_path']);
});

it('deletes a meeting summary and removes stored attachments', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $filePath = UploadedFile::fake()->create('signed.pdf', 20, 'application/pdf')->store('meeting-attachments', 'public');

    $meeting = MeetingSummary::create([
        'meeting_created_by' => $user->id,
        'meeting_type' => 'Client Meeting',
        'meeting_mode' => 'Online',
        'meeting_date' => '2026-05-14',
        'department' => 'Legal',
        'meeting_attended' => 'Employee with Client',
        'attendees' => [['employee_name' => 'Nina']],
        'agenda_items' => [[
            'agenda_point' => 'Contract review',
            'department' => 'Legal',
            'priority' => 'High',
            'attendance_status' => 'Present',
            'responsible_person' => 'Nina',
        ]],
        'action_items' => [[
            'task' => 'Finalize draft',
            'assigned_to' => 'Nina',
            'summary' => 'Draft',
            'deadline' => '2026-05-16',
            'details' => 'Send for review',
            'status' => 'Pending',
            'uploaded' => true,
        ]],
        'attachments' => [[
            'attachment_type' => 'Signed Documents',
            'uploaded' => true,
            'file_path' => $filePath,
            'original_name' => 'signed.pdf',
        ]],
    ]);

    Livewire::actingAs($user)
        ->test(MeetingSummaryManagement::class)
        ->call('deleteMeeting', $meeting->id);

    expect(MeetingSummary::find($meeting->id))->toBeNull();
    Storage::disk('public')->assertMissing($filePath);
});
