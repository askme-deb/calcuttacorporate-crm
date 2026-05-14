<div class="page-wrapper">
    <div class="page-content-tab">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <div class="float-end">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a wire:navigate href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Meeting Summary</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Meeting Summary</h4>
                    </div>
                </div>
            </div>

            @if (session()->has('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card">
                <div class="card-header">
                    <div class="row g-2 align-items-center">
                        <div class="col-md-8">
                            <input type="text" wire:model.live="search" class="form-control" placeholder="Search meeting type, client, company or department">
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="{{ route('meetings.summary.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add Meeting Summary
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Meeting Type</th>
                                    <th>Client</th>
                                    <th>Date</th>
                                    <th>Department</th>
                                    <th>Next Follow-Up</th>
                                    <th>Created By</th>
                                    <th>Attachments</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($meetings as $meetingRow)
                                    <tr>
                                        <td>{{ $meetingRow->meeting_type }}</td>
                                        <td>{{ $meetingRow->client_name ?: 'N/A' }}</td>
                                        <td>{{ optional($meetingRow->meeting_date)->format('d/m/Y') }}</td>
                                        <td>{{ $meetingRow->department ?: 'N/A' }}</td>
                                        <td>{{ optional($meetingRow->next_follow_up_date)->format('d/m/Y') ?: 'N/A' }}</td>
                                        <td>{{ optional($meetingRow->creator)->name ?: 'System' }}</td>
                                        <td>
                                            @php
                                                $attachments = $meetingRow->attachments ?? [];
                                                $downloaded = collect($attachments)->filter(fn($a) => !empty($a['file_path']))->values();
                                            @endphp
                                            @if ($downloaded->isNotEmpty())
                                                @foreach ($downloaded as $attach)
                                                    <a href="{{ Storage::url($attach['file_path']) }}" target="_blank" class="btn btn-link btn-sm p-0 me-2" title="Open attachment">
                                                        <i class="fas fa-paperclip"></i> {{ $attach['original_name'] ?: 'Attachment' }}
                                                    </a>
                                                @endforeach
                                            @else
                                                <span class="text-muted small">No files</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('meetings.summary.preview', $meetingRow->id) }}" class="btn btn-sm btn-info text-white">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('meetings.summary.print', $meetingRow->id) }}" target="_blank" class="btn btn-sm btn-secondary">
                                                <i class="fas fa-print"></i>
                                            </a>
                                            <a href="{{ route('meetings.summary.edit', $meetingRow->id) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="btn btn-sm btn-danger" wire:click="deleteMeeting({{ $meetingRow->id }})" wire:confirm="Delete this meeting summary?">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No meeting summaries found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    {{ $meetings->links() }}
                </div>
            </div>

            {{-- Modal form removed: now handled by dedicated create/edit pages --}}
        </div>
        <livewire:layout.footer />
    </div>
</div>
