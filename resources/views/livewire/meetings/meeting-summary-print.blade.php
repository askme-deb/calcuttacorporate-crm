@php
    $attendees = $meetingRecord->attendees ?? [];
    $agendaItems = $meetingRecord->agenda_items ?? [];
    $actionItems = $meetingRecord->action_items ?? [];
    $attachments = $meetingRecord->attachments ?? [];
    $reportNumber = 'MS-' . str_pad((string) $meetingRecord->id, 5, '0', STR_PAD_LEFT);
    $logoBase64 = null;
    $logoPath = public_path('assets/images/logo.jpeg');
    $companyLines = array_values(array_filter([
        config('app.company_address_line_1'),
        config('app.company_address_line_2'),
    ]));
    $companyPhone = config('app.company_phone');
    $companyGstin = config('app.company_gstin');
    $companyEmail = config('mail.from.address');
    $companyWebsite = parse_url(config('app.url'), PHP_URL_HOST) ?: config('app.url');

    if (file_exists($logoPath)) {
        $logoBase64 = base64_encode(file_get_contents($logoPath));
    }
@endphp

<div class="msprint-shell">
    @unless($isPdf ?? false)
    <div class="msprint-toolbar msprint-no-print">
        <a href="{{ route('meetings.summary.preview', $meetingRecord->id) }}" class="msprint-btn msprint-btn-light">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>
        <a href="{{ route('meetings.summary.pdf', $meetingRecord->id) }}" class="msprint-btn msprint-btn-light">
            <i class="fas fa-file-pdf"></i>
            Download
        </a>
        <button type="button" class="msprint-btn msprint-btn-dark" onclick="window.print()">
            <i class="fas fa-print"></i>
            Print
        </button>
    </div>
    @endunless

    <article class="msprint-document mt-5">
        <div class="msprint-watermark" aria-hidden="true">{{ config('app.name') ?: 'CRM' }}</div>
        <header class="msprint-document-header page-break-inside-avoid">
            <div class="msprint-brand-block">
                <div class="msprint-letterhead">
                    @if ($logoBase64)
                        <img src="data:image/jpeg;base64,{{ $logoBase64 }}" alt="{{ config('app.name') ?: 'CRM' }}" class="msprint-letterhead-logo">
                    @endif
                    <div class="msprint-letterhead-copy">
                        <div class="msprint-brand-kicker">{{ config('app.name') ?: 'CRM' }}</div>
                        <div class="msprint-letterhead-line">Internal Meeting Documentation</div>
                    </div>
                </div>
                <h1 class="msprint-title">Meeting Summary Report</h1>
                <p class="msprint-subtitle">Formal record of meeting details, agenda, outcomes, follow-up actions, and attachments.</p>
                @if (! empty($companyLines) || $companyPhone || $companyEmail || $companyWebsite || $companyGstin)
                    <div class="msprint-company-meta">
                        @if (! empty($companyLines))
                            <div class="msprint-company-meta-row">
                                <span>Address</span>
                                <strong>{{ implode(', ', $companyLines) }}</strong>
                            </div>
                        @endif
                        @if ($companyPhone)
                            <div class="msprint-company-meta-row">
                                <span>Phone</span>
                                <strong>{{ $companyPhone }}</strong>
                            </div>
                        @endif
                        @if ($companyEmail)
                            <div class="msprint-company-meta-row">
                                <span>Email</span>
                                <strong>{{ $companyEmail }}</strong>
                            </div>
                        @endif
                        @if ($companyWebsite)
                            <div class="msprint-company-meta-row">
                                <span>Website</span>
                                <strong>{{ $companyWebsite }}</strong>
                            </div>
                        @endif
                        @if ($companyGstin)
                            <div class="msprint-company-meta-row">
                                <span>GSTIN</span>
                                <strong>{{ $companyGstin }}</strong>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <div class="msprint-report-card">
                <div class="msprint-report-row">
                    <span>Report No.</span>
                    <strong>{{ $reportNumber }}</strong>
                </div>
                <div class="msprint-report-row">
                    <span>Meeting Date</span>
                    <strong>{{ optional($meetingRecord->meeting_date)->format('d M Y') ?: 'N/A' }}</strong>
                </div>
                <div class="msprint-report-row">
                    <span>Prepared By</span>
                    <strong>{{ optional($meetingRecord->creator)->name ?: 'System' }}</strong>
                </div>
                <div class="msprint-report-row">
                    <span>Department</span>
                    <strong>{{ $meetingRecord->department ?: 'N/A' }}</strong>
                </div>
            </div>
        </header>

        <section class="msprint-topline page-break-inside-avoid">
            <div class="msprint-topline-item">
                <span class="msprint-topline-label">Meeting Type</span>
                <strong class="msprint-topline-value">{{ $meetingRecord->meeting_type ?: 'N/A' }}</strong>
            </div>
            <div class="msprint-topline-item">
                <span class="msprint-topline-label">Mode</span>
                <strong class="msprint-topline-value">{{ $meetingRecord->meeting_mode ?: 'N/A' }}</strong>
            </div>
            <div class="msprint-topline-item">
                <span class="msprint-topline-label">Location</span>
                <strong class="msprint-topline-value">{{ $meetingRecord->meeting_location ?: 'N/A' }}</strong>
            </div>
            <div class="msprint-topline-item">
                <span class="msprint-topline-label">Time Window</span>
                <strong class="msprint-topline-value">{{ $meetingRecord->start_time ?: 'N/A' }} to {{ $meetingRecord->end_time ?: 'N/A' }}</strong>
            </div>
        </section>

        <section class="msprint-section page-break-inside-avoid">
            <div class="msprint-section-heading">
                <span class="msprint-section-index">01</span>
                <h2>Client And Contact Details</h2>
            </div>
            <div class="msprint-grid msprint-grid-2">
                <div class="msprint-fact">
                    <span class="msprint-fact-label">Client</span>
                    <strong class="msprint-fact-value">{{ $meetingRecord->client_name ?: optional($meetingRecord->client)->client_name ?: 'N/A' }}</strong>
                </div>
                <div class="msprint-fact">
                    <span class="msprint-fact-label">Company Name</span>
                    <strong class="msprint-fact-value">{{ $meetingRecord->company_name ?: 'N/A' }}</strong>
                </div>
                <div class="msprint-fact">
                    <span class="msprint-fact-label">Contact Number</span>
                    <strong class="msprint-fact-value">{{ $meetingRecord->contact_number ?: 'N/A' }}</strong>
                </div>
                <div class="msprint-fact">
                    <span class="msprint-fact-label">Contact Person</span>
                    <strong class="msprint-fact-value">{{ $meetingRecord->contact_person ?: 'N/A' }}</strong>
                </div>
                <div class="msprint-fact msprint-fact-wide">
                    <span class="msprint-fact-label">Meeting Attended</span>
                    <strong class="msprint-fact-value">{{ $meetingRecord->meeting_attended ?: 'N/A' }}</strong>
                </div>
            </div>
        </section>

        <section class="msprint-section page-break-inside-avoid">
            <div class="msprint-section-heading">
                <span class="msprint-section-index">02</span>
                <h2>Attendees</h2>
            </div>
            @if (count($attendees))
                <div class="msprint-attendee-list">
                    @foreach ($attendees as $index => $attendee)
                        <div class="msprint-attendee-item">
                            <span class="msprint-attendee-number">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="msprint-attendee-name">{{ $attendee['employee_name'] ?: 'N/A' }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="msprint-empty-note">No attendees recorded.</p>
            @endif
        </section>

        <section class="msprint-section">
            <div class="msprint-section-heading">
                <span class="msprint-section-index">03</span>
                <h2>Agenda</h2>
            </div>
            <div class="msprint-table-wrap">
                <table class="msprint-table">
                    <thead>
                        <tr>
                            <th style="width: 44px;">#</th>
                            <th>Agenda Point</th>
                            <th>Department</th>
                            <th>Priority</th>
                            <th>Attendance</th>
                            <th>Responsible Person</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($agendaItems as $index => $agendaItem)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $agendaItem['agenda_point'] ?: 'N/A' }}</td>
                                <td>{{ $agendaItem['department'] ?: 'N/A' }}</td>
                                <td>{{ $agendaItem['priority'] ?: 'N/A' }}</td>
                                <td>{{ $agendaItem['attendance_status'] ?: 'N/A' }}</td>
                                <td>{{ $agendaItem['responsible_person'] ?: 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="msprint-empty-cell">No agenda items recorded.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="msprint-section page-break-inside-avoid">
            <div class="msprint-section-heading">
                <span class="msprint-section-index">04</span>
                <h2>Meeting Summary / Report</h2>
            </div>
            <div class="msprint-prose-block">{{ $meetingRecord->discussion_point ?: 'No discussion points recorded.' }}</div>
        </section>

        <section class="msprint-section">
            <div class="msprint-section-heading">
                <span class="msprint-section-index">05</span>
                <h2>Follow-Up Action Items</h2>
            </div>
            <div class="msprint-table-wrap">
                <table class="msprint-table">
                    <thead>
                        <tr>
                            <th style="width: 44px;">#</th>
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
                        @forelse ($actionItems as $index => $actionItem)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $actionItem['task'] ?: 'N/A' }}</td>
                                <td>{{ $actionItem['assigned_to'] ?: 'N/A' }}</td>
                                <td>{{ !empty($actionItem['deadline']) ? \Carbon\Carbon::parse($actionItem['deadline'])->format('d M Y') : 'N/A' }}</td>
                                <td>{{ $actionItem['status'] ?: 'N/A' }}</td>
                                <td>{{ $actionItem['summary'] ?: 'N/A' }}</td>
                                <td>{{ $actionItem['details'] ?: 'N/A' }}</td>
                                <td>{{ !empty($actionItem['uploaded']) ? 'Yes' : 'No' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="msprint-empty-cell">No action items recorded.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="msprint-section page-break-inside-avoid">
            <div class="msprint-section-heading">
                <span class="msprint-section-index">06</span>
                <h2>Next Follow-Up</h2>
            </div>
            <div class="msprint-grid msprint-grid-2">
                <div class="msprint-fact">
                    <span class="msprint-fact-label">Next Follow-Up Date</span>
                    <strong class="msprint-fact-value">{{ optional($meetingRecord->next_follow_up_date)->format('d M Y') ?: 'N/A' }}</strong>
                </div>
                <div class="msprint-fact msprint-fact-wide">
                    <span class="msprint-fact-label">Follow-Up Action Summary</span>
                    <strong class="msprint-fact-value">{{ $meetingRecord->followup_action ?: 'N/A' }}</strong>
                </div>
                <div class="msprint-fact msprint-fact-wide">
                    <span class="msprint-fact-label">Next Follow-Up Details</span>
                    <strong class="msprint-fact-value msprint-multiline">{{ $meetingRecord->next_follow_up_details ?: 'N/A' }}</strong>
                </div>
            </div>
        </section>

        <section class="msprint-section page-break-inside-avoid">
            <div class="msprint-section-heading">
                <span class="msprint-section-index">07</span>
                <h2>Attachments</h2>
            </div>
            <div class="msprint-table-wrap">
                <table class="msprint-table">
                    <thead>
                        <tr>
                            <th style="width: 44px;">#</th>
                            <th>Attachment Type</th>
                            <th>Status</th>
                            <th>File Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($attachments as $index => $attachment)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $attachment['attachment_type'] ?: 'N/A' }}</td>
                                <td>{{ !empty($attachment['file_path']) ? 'Uploaded' : 'Pending' }}</td>
                                <td>{{ $attachment['original_name'] ?: 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="msprint-empty-cell">No attachments configured.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="msprint-section page-break-inside-avoid">
            <div class="msprint-section-heading">
                <span class="msprint-section-index">08</span>
                <h2>Sign-Off</h2>
            </div>
            <div class="msprint-signoff-grid">
                <div class="msprint-signoff-box">
                    <div class="msprint-signoff-label">Prepared By</div>
                    <div class="msprint-signoff-line"></div>
                    <div class="msprint-signoff-name">{{ optional($meetingRecord->creator)->name ?: 'Authorized Person' }}</div>
                </div>
                <div class="msprint-signoff-box">
                    <div class="msprint-signoff-label">Reviewed By</div>
                    <div class="msprint-signoff-line"></div>
                    <div class="msprint-signoff-name">____________________________</div>
                </div>
                <div class="msprint-signoff-box">
                    <div class="msprint-signoff-label">Approved By</div>
                    <div class="msprint-signoff-line"></div>
                    <div class="msprint-signoff-name">____________________________</div>
                </div>
            </div>
        </section>

        <footer class="msprint-footer page-break-inside-avoid">
            <div class="msprint-footer-left">
                <div class="msprint-footer-title">Document Control</div>
                <div>Generated on {{ now()->format('d M Y, h:i A') }}</div>
            </div>
            <div class="msprint-footer-right">
                <div>{{ config('app.name') ?: 'CRM' }}</div>
                <div>{{ $reportNumber }}</div>
            </div>
        </footer>
    </article>
    <div class="msprint-page-counter print-only" aria-hidden="true"></div>
    <style>
    :root {
        --msprint-ink: #182230;
        --msprint-subtle: #667085;
        --msprint-line: #d0d7e2;
        --msprint-line-strong: #98a5b7;
        --msprint-soft: #f7f9fc;
        --msprint-soft-2: #eef3f8;
        --msprint-paper: #ffffff;
        --msprint-accent: #1f4f7f;
        --msprint-accent-soft: #eaf1f7;
        --msprint-shadow: 0 18px 50px rgba(15, 23, 42, 0.08);
    }

    body {
        margin: 0;
        background: #eef2f6;
        color: var(--msprint-ink);
        font-family: "Segoe UI", Arial, sans-serif;
    }

    .msprint-shell {
        padding: 24px;
    }

    .msprint-toolbar {
        position: fixed;
        top: 88px;
        right: 24px;
        z-index: 9999;
        margin: 0;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        padding: 10px;
        background: rgba(255, 255, 255, 0.96);
        border: 1px solid rgba(152, 165, 183, 0.45);
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.12);
        backdrop-filter: blur(6px);
    }

    .msprint-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 16px;
        border-radius: 8px;
        border: 1px solid transparent;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        line-height: 1;
        cursor: pointer;
    }

    .msprint-btn-light {
        color: var(--msprint-ink);
        background: #ffffff;
        border-color: var(--msprint-line);
    }

    .msprint-btn-dark {
        color: #ffffff;
        background: #111827;
    }

    .msprint-document {
        position: relative;
        max-width: 1024px;
        margin: 0 auto;
        background: var(--msprint-paper);
        box-shadow: var(--msprint-shadow);
        border: 1px solid rgba(152, 165, 183, 0.4);
        padding: 38px 42px 30px;
        overflow: hidden;
    }

    .msprint-document > * {
        position: relative;
        z-index: 1;
    }

    .msprint-watermark {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 72px;
        font-weight: 700;
        letter-spacing: 0.16em;
        text-transform: uppercase;
        color: rgba(31, 79, 127, 0.05);
        transform: rotate(-30deg) translateY(10px);
        pointer-events: none;
        z-index: 0;
    }

    .msprint-document-header {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 280px;
        gap: 28px;
        align-items: start;
        padding-bottom: 22px;
        border-bottom: 2px solid var(--msprint-line-strong);
    }

    .msprint-letterhead {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 10px;
    }

    .msprint-letterhead-logo {
        width: 220px;
        max-width: 100%;
        height: auto;
        object-fit: contain;
    }

    .msprint-letterhead-copy {
        display: flex;
        flex-direction: column;
        gap: 3px;
    }

    .msprint-letterhead-line {
        color: var(--msprint-subtle);
        font-size: 12px;
        letter-spacing: 0.06em;
        text-transform: uppercase;
    }

    .msprint-brand-kicker {
        margin-bottom: 8px;
        color: var(--msprint-accent);
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.18em;
        text-transform: uppercase;
    }

    .msprint-title {
        margin: 0;
        font-size: 30px;
        line-height: 1.1;
        letter-spacing: -0.03em;
    }

    .msprint-subtitle {
        margin: 10px 0 0;
        max-width: 620px;
        color: var(--msprint-subtle);
        font-size: 14px;
        line-height: 1.6;
    }

    .msprint-company-meta {
        margin-top: 14px;
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 8px 18px;
        max-width: 760px;
        padding-top: 12px;
        border-top: 1px dashed var(--msprint-line);
    }

    .msprint-company-meta-row {
        display: flex;
        gap: 8px;
        font-size: 12px;
        line-height: 1.5;
    }

    .msprint-company-meta-row span {
        min-width: 62px;
        color: var(--msprint-subtle);
        text-transform: uppercase;
        letter-spacing: 0.08em;
        font-size: 10px;
        font-weight: 700;
    }

    .msprint-company-meta-row strong {
        font-weight: 600;
    }

    .msprint-report-card {
        border: 1px solid var(--msprint-line);
        background: linear-gradient(180deg, #fbfcfe 0%, #f4f7fb 100%);
        padding: 14px 16px;
    }

    .msprint-report-row {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        padding: 8px 0;
        font-size: 12.5px;
        border-bottom: 1px solid var(--msprint-line);
    }

    .msprint-report-row:last-child {
        border-bottom: 0;
    }

    .msprint-report-row span {
        color: var(--msprint-subtle);
    }

    .msprint-topline {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        margin: 20px 0 28px;
        border: 1px solid var(--msprint-line);
        background: var(--msprint-soft);
    }

    .msprint-topline-item {
        padding: 14px 16px;
        border-right: 1px solid var(--msprint-line);
    }

    .msprint-topline-item:last-child {
        border-right: 0;
    }

    .msprint-topline-label,
    .msprint-fact-label {
        display: block;
        margin-bottom: 7px;
        color: var(--msprint-subtle);
        font-size: 10.5px;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
    }

    .msprint-topline-value,
    .msprint-fact-value {
        display: block;
        font-size: 13.5px;
        font-weight: 600;
        line-height: 1.55;
    }

    .msprint-section {
        margin-bottom: 26px;
    }

    .msprint-section-heading {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
        padding-bottom: 8px;
        border-bottom: 1px solid var(--msprint-line-strong);
    }

    .msprint-section-index {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        background: var(--msprint-accent-soft);
        color: var(--msprint-accent);
        font-size: 11px;
        font-weight: 700;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .msprint-section-heading h2 {
        margin: 0;
        font-size: 15px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--msprint-accent);
    }

    .msprint-grid {
        display: grid;
        gap: 12px 16px;
    }

    .msprint-grid-2 {
        grid-template-columns: repeat(2, 1fr);
    }

    .msprint-fact {
        min-height: 74px;
        padding: 13px 15px;
        border: 1px solid var(--msprint-line);
        background: #ffffff;
    }

    .msprint-fact-wide {
        grid-column: 1 / -1;
    }

    .msprint-multiline,
    .msprint-prose-block {
        white-space: pre-line;
    }

    .msprint-attendee-list {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px 14px;
    }

    .msprint-attendee-item {
        display: flex;
        align-items: center;
        gap: 10px;
        min-height: 44px;
        padding: 10px 12px;
        border: 1px solid var(--msprint-line);
        background: var(--msprint-soft);
    }

    .msprint-attendee-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background: #ffffff;
        border: 1px solid var(--msprint-line);
        font-size: 11px;
        font-weight: 700;
    }

    .msprint-attendee-name {
        font-size: 13.5px;
        font-weight: 600;
    }

    .msprint-table-wrap {
        overflow: hidden;
        border: 1px solid var(--msprint-line);
    }

    .msprint-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12.5px;
    }

    .msprint-table thead {
        display: table-header-group;
    }

    .msprint-table tr {
        page-break-inside: avoid;
        break-inside: avoid;
    }

    .msprint-table th,
    .msprint-table td {
        padding: 10px 11px;
        text-align: left;
        vertical-align: top;
        border-right: 1px solid var(--msprint-line);
        border-bottom: 1px solid var(--msprint-line);
    }

    .msprint-table th:last-child,
    .msprint-table td:last-child {
        border-right: 0;
    }

    .msprint-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .msprint-table thead th {
        background: var(--msprint-soft-2);
        font-size: 10.5px;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .msprint-prose-block {
        min-height: 88px;
        padding: 16px 18px;
        border: 1px solid var(--msprint-line);
        background: #fcfdff;
        line-height: 1.7;
        font-size: 13px;
    }

    .msprint-empty-note,
    .msprint-empty-cell {
        color: var(--msprint-subtle);
        text-align: center;
    }

    .msprint-footer {
        display: flex;
        justify-content: space-between;
        gap: 16px;
        margin-top: 30px;
        padding-top: 16px;
        border-top: 1.5px solid var(--msprint-line-strong);
        color: var(--msprint-subtle);
        font-size: 11px;
    }

    .msprint-footer-title {
        margin-bottom: 4px;
        color: var(--msprint-ink);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }

    .msprint-footer-right {
        text-align: right;
    }

    .msprint-signoff-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 18px;
        padding-top: 8px;
    }

    .msprint-signoff-box {
        min-height: 110px;
        padding: 16px 12px 8px;
        border: 1px solid var(--msprint-line);
        background: #fff;
    }

    .msprint-signoff-label {
        margin-bottom: 34px;
        color: var(--msprint-subtle);
        font-size: 10.5px;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
    }

    .msprint-signoff-line {
        border-bottom: 1.5px solid var(--msprint-line-strong);
        margin-bottom: 8px;
    }

    .msprint-signoff-name {
        font-size: 12px;
        color: var(--msprint-subtle);
    }

    .print-only {
        display: none;
    }

    .msprint-page-counter::after {
        content: "Page " counter(page);
    }

    .page-break-inside-avoid {
        break-inside: avoid;
        page-break-inside: avoid;
    }

    @media (max-width: 900px) {
        .msprint-shell {
            padding: 72px 14px 14px;
        }

        .msprint-document {
            padding: 24px 20px;
        }

        .msprint-document-header,
        .msprint-topline,
        .msprint-grid-2,
        .msprint-attendee-list,
        .msprint-signoff-grid,
        .msprint-company-meta {
            grid-template-columns: 1fr;
        }

        .msprint-letterhead {
            flex-direction: column;
            align-items: flex-start;
        }

        .msprint-topline-item {
            border-right: 0;
            border-bottom: 1px solid var(--msprint-line);
        }

        .msprint-topline-item:last-child {
            border-bottom: 0;
        }

        .msprint-footer {
            flex-direction: column;
        }

        .msprint-toolbar {
            top: 12px;
            right: 12px;
            left: 12px;
            justify-content: stretch;
        }

        .msprint-btn {
            justify-content: center;
            flex: 1 1 auto;
        }

        .msprint-footer-right {
            text-align: left;
        }
    }

    @media print {
        @page {
            size: A4;
            margin: 10mm 11mm 12mm;
        }

        html,
        body {
            background: #ffffff;
            margin: 0;
            padding: 0;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .msprint-no-print {
            display: none !important;
        }

        .print-only {
            display: block;
        }

        .msprint-shell {
            padding: 0;
        }

        .msprint-document {
            max-width: none;
            margin: 0;
            padding: 0;
            border: 0;
            box-shadow: none;
        }

        .msprint-watermark {
            position: fixed;
            inset: 0;
            font-size: 82px;
        }

        .msprint-page-counter {
            position: fixed;
            right: 0;
            bottom: 0;
            color: var(--msprint-subtle);
            font-size: 10px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .msprint-section,
        .msprint-document-header,
        .msprint-topline,
        .msprint-footer {
            break-inside: avoid;
            page-break-inside: avoid;
        }

        a {
            color: inherit;
            text-decoration: none;
        }
    }
</style>

</div>

