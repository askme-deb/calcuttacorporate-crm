<div class="page-wrapper">
    <style>
/* ─── Design Tokens ───────────────────────────────────────────── */
:root {
    --msm-bg:           #f4f6fb;
    --msm-card-bg:      #ffffff;
    --msm-border:       #e8ecf4;
    --msm-border-light: #f0f3fa;

    --msm-primary:      #2563eb;
    --msm-primary-dim:  #dbeafe;
    --msm-primary-dark: #1d4ed8;

    --msm-text-head:    #0f172a;
    --msm-text-body:    #374151;
    --msm-text-muted:   #9ca3af;
    --msm-text-label:   #6b7280;

    --msm-success:      #16a34a;
    --msm-success-bg:   #dcfce7;
    --msm-warning:      #d97706;
    --msm-warning-bg:   #fef3c7;
    --msm-danger:       #dc2626;
    --msm-danger-bg:    #fee2e2;
    --msm-info:         #0891b2;
    --msm-info-bg:      #cffafe;

    --msm-radius-sm:    6px;
    --msm-radius:       10px;
    --msm-radius-lg:    14px;

    --msm-shadow-sm:    0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
    --msm-shadow:       0 4px 16px rgba(0,0,0,.07), 0 1px 4px rgba(0,0,0,.04);
    --msm-shadow-lg:    0 10px 32px rgba(0,0,0,.10), 0 2px 8px rgba(0,0,0,.05);

    --msm-font:         'DM Sans', 'Segoe UI', sans-serif;
}

/* ─── Page Header ────────────────────────────────────────────── */
.msm-page-header {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    padding: 8px 0 4px;
}
.msm-module-label {
    display: block;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: var(--msm-primary);
    margin-bottom: 2px;
}
.msm-page-title {
    margin: 0;
    font-size: 22px;
    font-weight: 700;
    color: var(--msm-text-head);
    letter-spacing: -.3px;
}
.msm-breadcrumb {
    display: flex;
    align-items: center;
    gap: 6px;
    list-style: none;
    margin: 0;
    padding: 0;
    font-size: 12.5px;
    color: var(--msm-text-muted);
}
.msm-breadcrumb a {
    color: var(--msm-primary);
    text-decoration: none;
}
.msm-breadcrumb a:hover { text-decoration: underline; }
.msm-breadcrumb .separator { font-size: 9px; color: var(--msm-border); }
.msm-breadcrumb .active { color: var(--msm-text-label); font-weight: 500; }

/* ─── Alert ──────────────────────────────────────────────────── */
.msm-alert-success {
    display: flex;
    align-items: center;
    gap: 10px;
    background: var(--msm-success-bg);
    border: 1px solid #bbf7d0;
    color: var(--msm-success);
    border-radius: var(--msm-radius);
    padding: 12px 16px;
    margin-bottom: 20px;
    font-size: 13.5px;
    font-weight: 500;
}
.msm-alert-close {
    margin-left: auto;
    background: none;
    border: none;
    color: var(--msm-success);
    cursor: pointer;
    padding: 2px 4px;
    opacity: .7;
}
.msm-alert-close:hover { opacity: 1; }

/* ─── Card ───────────────────────────────────────────────────── */
.msm-card {
    background: var(--msm-card-bg);
    border: 1px solid var(--msm-border);
    border-radius: var(--msm-radius-lg);
    box-shadow: var(--msm-shadow);
    overflow: hidden;
}

/* ─── Card Header ────────────────────────────────────────────── */
.msm-card-header {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 18px 24px;
    border-bottom: 1px solid var(--msm-border-light);
    background: #fafbff;
}
.msm-search-wrapper {
    position: relative;
    flex: 1;
}
.msm-search-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--msm-text-muted);
    font-size: 13px;
    pointer-events: none;
}
.msm-search-input {
    width: 100%;
    height: 40px;
    padding: 0 14px 0 38px;
    border: 1px solid var(--msm-border);
    border-radius: var(--msm-radius);
    font-size: 13.5px;
    color: var(--msm-text-body);
    background: #fff;
    transition: border-color .15s, box-shadow .15s;
    outline: none;
}
.msm-search-input:focus {
    border-color: var(--msm-primary);
    box-shadow: 0 0 0 3px rgba(37,99,235,.1);
}
.msm-search-input::placeholder { color: var(--msm-text-muted); }

.msm-btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    height: 40px;
    padding: 0 20px;
    background: var(--msm-primary);
    color: #fff !important;
    border: none;
    border-radius: var(--msm-radius);
    font-size: 13.5px;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    white-space: nowrap;
    transition: background .15s, box-shadow .15s, transform .1s;
    box-shadow: 0 2px 6px rgba(37,99,235,.3);
}
.msm-btn-primary:hover {
    background: var(--msm-primary-dark);
    box-shadow: 0 4px 12px rgba(37,99,235,.35);
    transform: translateY(-1px);
    color: #fff !important;
}
.msm-btn-primary:active { transform: translateY(0); }

/* ─── Card Body ──────────────────────────────────────────────── */
.msm-card-body { padding: 0; }

/* ─── Table ──────────────────────────────────────────────────── */
.msm-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13.5px;
}
.msm-table thead tr {
    background: #f8faff;
    border-bottom: 2px solid var(--msm-border);
}
.msm-table thead th {
    padding: 13px 18px;
    font-size: 11.5px;
    font-weight: 700;
    letter-spacing: .06em;
    text-transform: uppercase;
    color: var(--msm-text-label);
    white-space: nowrap;
}
.msm-table tbody tr {
    border-bottom: 1px solid var(--msm-border-light);
    transition: background .12s;
}
.msm-table tbody tr:last-child { border-bottom: none; }
.msm-table tbody tr:hover { background: #f8faff; }
.msm-table tbody td {
    padding: 14px 18px;
    color: var(--msm-text-body);
    vertical-align: middle;
}

/* ─── Cell Variants ──────────────────────────────────────────── */
.msm-cell-primary {
    font-weight: 500;
    color: var(--msm-text-head);
}
.msm-cell-muted {
    color: var(--msm-text-muted);
    font-size: 13px;
}
.msm-cell-date {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: var(--msm-text-body);
    font-variant-numeric: tabular-nums;
}
.msm-cell-date i { color: var(--msm-text-muted); font-size: 12px; }

.msm-cell-followup {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: var(--msm-warning);
    font-weight: 500;
    font-size: 13px;
}
.msm-cell-followup i { font-size: 11px; }

/* ─── Badges ─────────────────────────────────────────────────── */
.msm-badge-type {
    display: inline-block;
    padding: 3px 10px;
    background: var(--msm-primary-dim);
    color: var(--msm-primary-dark);
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    white-space: nowrap;
}
.msm-badge-dept {
    display: inline-block;
    padding: 3px 10px;
    background: #f3f4f6;
    color: #374151;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    border: 1px solid #e5e7eb;
}

/* ─── Creator Avatar ─────────────────────────────────────────── */
.msm-creator {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: var(--msm-text-body);
}
.msm-creator-avatar {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--msm-primary) 0%, #60a5fa 100%);
    color: #fff;
    font-size: 11px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

/* ─── Attachments ────────────────────────────────────────────── */
.msm-attachments {
    display: flex;
    flex-direction: column;
    gap: 4px;
}
.msm-attachment-link {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 12px;
    color: var(--msm-primary);
    text-decoration: none;
    padding: 2px 0;
}
.msm-attachment-link i { font-size: 11px; }
.msm-attachment-link:hover { text-decoration: underline; }

/* ─── Action Buttons ─────────────────────────────────────────── */
.msm-actions {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}
.msm-action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: var(--msm-radius-sm);
    border: 1px solid transparent;
    font-size: 12.5px;
    cursor: pointer;
    text-decoration: none;
    transition: background .15s, color .15s, border-color .15s, transform .1s;
    background: none;
    padding: 0;
}
.msm-action-btn:hover { transform: translateY(-1px); }

.msm-action-view {
    background: var(--msm-info-bg);
    color: var(--msm-info);
    border-color: #a5f3fc;
}
.msm-action-view:hover {
    background: var(--msm-info);
    color: #fff;
    border-color: var(--msm-info);
}
.msm-action-edit {
    background: var(--msm-warning-bg);
    color: var(--msm-warning);
    border-color: #fcd34d;
}
.msm-action-edit:hover {
    background: var(--msm-warning);
    color: #fff;
    border-color: var(--msm-warning);
}
.msm-action-delete {
    background: var(--msm-danger-bg);
    color: var(--msm-danger);
    border-color: #fca5a5;
}
.msm-action-delete:hover {
    background: var(--msm-danger);
    color: #fff;
    border-color: var(--msm-danger);
}

/* ─── Empty State ────────────────────────────────────────────── */
.msm-empty-state {
    text-align: center;
    padding: 60px 24px;
}
.msm-empty-icon {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    background: var(--msm-primary-dim);
    color: var(--msm-primary);
    font-size: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
}
.msm-empty-title {
    font-size: 15px;
    font-weight: 600;
    color: var(--msm-text-head);
    margin: 0 0 4px;
}
.msm-empty-sub {
    font-size: 13px;
    color: var(--msm-text-muted);
    margin: 0;
}

/* ─── Card Footer / Pagination ───────────────────────────────── */
.msm-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 24px;
    border-top: 1px solid var(--msm-border-light);
    background: #fafbff;
    flex-wrap: wrap;
    gap: 10px;
}
.msm-pagination-info {
    font-size: 12.5px;
    color: var(--msm-text-muted);
}

/* Override Laravel pagination to match design */
.msm-pagination-links nav { display: flex; align-items: center; }
.msm-pagination-links .pagination {
    display: flex;
    gap: 4px;
    margin: 0;
    padding: 0;
    list-style: none;
}
.msm-pagination-links .page-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 32px;
    height: 32px;
    padding: 0 8px;
    border: 1px solid var(--msm-border);
    border-radius: var(--msm-radius-sm);
    font-size: 13px;
    color: var(--msm-text-body);
    text-decoration: none;
    transition: all .15s;
    background: #fff;
}
.msm-pagination-links .page-link:hover {
    background: var(--msm-primary-dim);
    border-color: var(--msm-primary);
    color: var(--msm-primary);
}
.msm-pagination-links .page-item.active .page-link {
    background: var(--msm-primary);
    border-color: var(--msm-primary);
    color: #fff;
    font-weight: 600;
}
.msm-pagination-links .page-item.disabled .page-link {
    opacity: .4;
    pointer-events: none;
}

/* ─── Responsive ─────────────────────────────────────────────── */
@media (max-width: 768px) {
    .msm-card-header { flex-direction: column; align-items: stretch; }
    .msm-btn-primary { justify-content: center; }
    .msm-card-footer { flex-direction: column; align-items: flex-start; }
    .msm-page-header { flex-direction: column; align-items: flex-start; gap: 8px; }
}
</style>

    <div class="page-content-tab">
        <div class="container-fluid">

            {{-- Page Header --}}
            <div class="row mb-4">
                <div class="col-12">
                    <div class="msm-page-header">
                        <div class="msm-header-left">
                            <div class="msm-title-group">
                                <span class="msm-module-label">Meetings</span>
                                <h4 class="msm-page-title">Meeting Summary</h4>
                            </div>
                        </div>
                        <div class="msm-header-right">
                            <ol class="msm-breadcrumb">
                                <li><a wire:navigate href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li class="separator"><i class="fas fa-chevron-right"></i></li>
                                <li class="active">Meeting Summary</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Flash Message --}}
            @if (session()->has('success'))
                <div class="msm-alert-success">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('success') }}</span>
                    <button type="button" class="msm-alert-close" onclick="this.parentElement.style.display='none'">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif

            {{-- Main Card --}}
            <div class="msm-card">

                {{-- Card Header: Search + Action --}}
                <div class="msm-card-header">
                    <div class="msm-search-wrapper">
                        <i class="fas fa-search msm-search-icon"></i>
                        <input type="text"
                               wire:model.live="search"
                               class="msm-search-input"
                               placeholder="Search by type, client, company or department…">
                    </div>
                    <a href="{{ route('meetings.summary.create') }}" class="msm-btn-primary">
                        <i class="fas fa-plus"></i>
                        <span>New Meeting</span>
                    </a>
                </div>

                {{-- Table --}}
                <div class="msm-card-body">
                    <div class="table-responsive">
                        <table class="msm-table">
                            <thead>
                                <tr>
                                    <th>Meeting Type</th>
                                    <th>Client</th>
                                    <th>Date</th>
                                    <th>Department</th>
                                    <th>Next Follow-Up</th>
                                    <th>Created By</th>
                                    <th>Attachments</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($meetings as $meetingRow)
                                    <tr>
                                        <td>
                                            <span class="msm-badge-type">{{ $meetingRow->meeting_type }}</span>
                                        </td>
                                        <td>
                                            <span class="msm-cell-primary">{{ $meetingRow->client_name ?: '—' }}</span>
                                        </td>
                                        <td>
                                            <span class="msm-cell-date">
                                                <i class="fas fa-calendar-alt"></i>
                                                {{ optional($meetingRow->meeting_date)->format('d M Y') ?: '—' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($meetingRow->department)
                                                <span class="msm-badge-dept">{{ $meetingRow->department }}</span>
                                            @else
                                                <span class="msm-cell-muted">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($meetingRow->next_follow_up_date)
                                                <span class="msm-cell-followup">
                                                    <i class="fas fa-clock"></i>
                                                    {{ optional($meetingRow->next_follow_up_date)->format('d M Y') }}
                                                </span>
                                            @else
                                                <span class="msm-cell-muted">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="msm-creator">
                                                <div class="msm-creator-avatar">
                                                    {{ strtoupper(substr(optional($meetingRow->creator)->name ?: 'S', 0, 1)) }}
                                                </div>
                                                <span>{{ optional($meetingRow->creator)->name ?: 'System' }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $attachments = $meetingRow->attachments ?? [];
                                                $downloaded = collect($attachments)->filter(fn($a) => !empty($a['file_path']))->values();
                                            @endphp
                                            @if ($downloaded->isNotEmpty())
                                                <div class="msm-attachments">
                                                    @foreach ($downloaded as $attach)
                                                        <a href="{{ Storage::url($attach['file_path']) }}"
                                                           target="_blank"
                                                           class="msm-attachment-link"
                                                           title="{{ $attach['original_name'] ?: 'Attachment' }}">
                                                            <i class="fas fa-paperclip"></i>
                                                            <span>{{ Str::limit($attach['original_name'] ?: 'File', 18) }}</span>
                                                        </a>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="msm-cell-muted">No files</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="msm-actions">
                                                <a href="{{ route('meetings.summary.preview', $meetingRow->id) }}"
                                                   class="msm-action-btn msm-action-view"
                                                   title="Preview">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('meetings.summary.edit', $meetingRow->id) }}"
                                                   class="msm-action-btn msm-action-edit"
                                                   title="Edit">
                                                    <i class="fas fa-pen"></i>
                                                </a>
                                                <button class="msm-action-btn msm-action-delete"
                                                        wire:click="deleteMeeting({{ $meetingRow->id }})"
                                                        wire:confirm="Are you sure you want to delete this meeting summary?"
                                                        title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8">
                                            <div class="msm-empty-state">
                                                <div class="msm-empty-icon">
                                                    <i class="fas fa-calendar-times"></i>
                                                </div>
                                                <p class="msm-empty-title">No meeting summaries found</p>
                                                <p class="msm-empty-sub">Try adjusting your search or create a new meeting.</p>
                                                <a href="{{ route('meetings.summary.create') }}" class="msm-btn-primary mt-3">
                                                    <i class="fas fa-plus"></i> New Meeting
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Pagination --}}
                <div class="msm-card-footer">
                    <div class="msm-pagination-info">
                        Showing {{ $meetings->firstItem() ?? 0 }}–{{ $meetings->lastItem() ?? 0 }}
                        of {{ $meetings->total() }} records
                    </div>
                    <div class="msm-pagination-links">
                        {{ $meetings->links() }}
                    </div>
                </div>

            </div>{{-- /.msm-card --}}

        </div>
        <livewire:layout.footer />
    </div>
</div>

