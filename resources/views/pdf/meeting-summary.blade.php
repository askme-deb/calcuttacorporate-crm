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
    $attendeeRows = array_chunk($attendees, 2);

    if (file_exists($logoPath)) {
        $logoBase64 = base64_encode(file_get_contents($logoPath));
    }
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Meeting Summary {{ $reportNumber }}</title>
    <style>
        @page {
            size: A4;
            margin: 12mm 10mm 14mm;
        }

        body {
            margin: 0;
            color: #182230;
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            line-height: 1.45;
        }

        .pdf-document {
            position: relative;
        }

        .watermark {
            position: fixed;
            top: 34%;
            left: 8%;
            width: 84%;
            text-align: center;
            font-size: 62px;
            font-weight: 700;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            color: rgba(31, 79, 127, 0.05);
            transform: rotate(-30deg);
            z-index: -1;
        }

        .header,
        .section,
        .footer {
            page-break-inside: avoid;
        }

        .header {
            width: 100%;
            border-bottom: 2px solid #98a5b7;
            padding-bottom: 14px;
            margin-bottom: 16px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            vertical-align: top;
        }

        .brand-block {
            width: 68%;
            padding-right: 18px;
        }

        .report-block {
            width: 32%;
        }

        .letterhead-logo {
            max-width: 210px;
            max-height: 80px;
            height: auto;
            display: block;
            margin-bottom: 6px;
        }

        .brand-kicker {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.18em;
            color: #1f4f7f;
        }

        .letterhead-line {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #667085;
            margin-top: 2px;
        }

        .title {
            margin: 8px 0 4px;
            font-size: 25px;
            line-height: 1.1;
            font-weight: 700;
            color: #182230;
        }

        .subtitle {
            margin: 0 0 10px;
            color: #667085;
            font-size: 11px;
        }

        .company-meta {
            margin-top: 10px;
            padding-top: 8px;
            border-top: 1px dashed #d0d7e2;
        }

        .company-meta div {
            margin-bottom: 4px;
        }

        .company-meta strong {
            display: inline-block;
            min-width: 58px;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #667085;
            margin-right: 6px;
        }

        .meta-table {
            border: 1px solid #d0d7e2;
            background: #f7f9fc;
        }

        .meta-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #d0d7e2;
        }

        .meta-table tr:last-child td {
            border-bottom: 0;
        }

        .meta-label {
            color: #667085;
            width: 38%;
        }

        .summary-table {
            margin-bottom: 16px;
            border: 1px solid #d0d7e2;
        }

        .summary-table td {
            width: 25%;
            padding: 10px 12px;
            border-right: 1px solid #d0d7e2;
            background: #f7f9fc;
            vertical-align: top;
        }

        .summary-table td:last-child {
            border-right: 0;
        }

        .summary-label,
        .fact-label {
            display: block;
            margin-bottom: 4px;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #667085;
        }

        .summary-value,
        .fact-value {
            font-size: 11px;
            font-weight: 600;
        }

        .section {
            margin-bottom: 16px;
        }

        .section-title {
            margin: 0 0 8px;
            padding-bottom: 6px;
            border-bottom: 1px solid #98a5b7;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #1f4f7f;
        }

        .fact-grid td {
            width: 50%;
            border: 1px solid #d0d7e2;
            padding: 10px 12px;
            vertical-align: top;
        }

        .attendee-list td {
            width: 50%;
            border: 1px solid #d0d7e2;
            padding: 9px 10px;
            vertical-align: top;
        }

        .attendee-index {
            display: inline-block;
            width: 24px;
            font-size: 10px;
            font-weight: 700;
            color: #667085;
        }

        .data-table {
            border: 1px solid #d0d7e2;
        }

        .data-table thead th {
            padding: 8px 9px;
            border-right: 1px solid #d0d7e2;
            border-bottom: 1px solid #d0d7e2;
            background: #eef3f8;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            text-align: left;
        }

        .data-table thead th:last-child,
        .data-table tbody td:last-child {
            border-right: 0;
        }

        .data-table tbody td {
            padding: 8px 9px;
            border-right: 1px solid #d0d7e2;
            border-bottom: 1px solid #d0d7e2;
            vertical-align: top;
        }

        .data-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .prose-block {
            border: 1px solid #d0d7e2;
            padding: 12px;
            white-space: pre-line;
            min-height: 70px;
        }

        .empty {
            color: #667085;
            text-align: center;
        }

        .signoff-table td {
            width: 33.33%;
            padding-right: 14px;
            vertical-align: top;
        }

        .signoff-table td:last-child {
            padding-right: 0;
        }

        .signoff-box {
            border: 1px solid #d0d7e2;
            padding: 12px 10px 10px;
            min-height: 84px;
        }

        .signoff-label {
            margin-bottom: 28px;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #667085;
        }

        .signoff-line {
            border-bottom: 1px solid #98a5b7;
            margin-bottom: 6px;
        }

        .signoff-name {
            color: #667085;
            font-size: 10px;
        }

        .footer {
            margin-top: 16px;
            padding-top: 10px;
            border-top: 1px solid #98a5b7;
            font-size: 10px;
            color: #667085;
        }

        .footer-left {
            float: left;
        }

        .footer-right {
            float: right;
            text-align: right;
        }

        .clearfix::after {
            content: "";
            display: block;
            clear: both;
        }
    </style>
</head>
<body>
<div class="pdf-document">
    <div class="watermark">{{ config('app.name') ?: 'CRM' }}</div>

    <div class="header">
        <table class="header-table">
            <tr>
                <td class="brand-block">
                    @if ($logoBase64)
                        <img src="data:image/jpeg;base64,{{ $logoBase64 }}" alt="{{ config('app.name') ?: 'CRM' }}" class="letterhead-logo">
                    @endif
                    <div class="brand-kicker">{{ config('app.name') ?: 'CRM' }}</div>
                    <div class="letterhead-line">Internal Meeting Documentation</div>
                    <div class="title">Meeting Summary Report</div>
                    <div class="subtitle">Formal record of meeting details, agenda, outcomes, follow-up actions, and attachments.</div>

                    @if (! empty($companyLines) || $companyPhone || $companyEmail || $companyWebsite || $companyGstin)
                        <div class="company-meta">
                            @if (! empty($companyLines))
                                <div><strong>Address</strong>{{ implode(', ', $companyLines) }}</div>
                            @endif
                            @if ($companyPhone)
                                <div><strong>Phone</strong>{{ $companyPhone }}</div>
                            @endif
                            @if ($companyEmail)
                                <div><strong>Email</strong>{{ $companyEmail }}</div>
                            @endif
                            @if ($companyWebsite)
                                <div><strong>Website</strong>{{ $companyWebsite }}</div>
                            @endif
                            @if ($companyGstin)
                                <div><strong>GSTIN</strong>{{ $companyGstin }}</div>
                            @endif
                        </div>
                    @endif
                </td>
                <td class="report-block">
                    <table class="meta-table">
                        <tr>
                            <td class="meta-label">Report No.</td>
                            <td>{{ $reportNumber }}</td>
                        </tr>
                        <tr>
                            <td class="meta-label">Meeting Date</td>
                            <td>{{ optional($meetingRecord->meeting_date)->format('d M Y') ?: 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="meta-label">Prepared By</td>
                            <td>{{ optional($meetingRecord->creator)->name ?: 'System' }}</td>
                        </tr>
                        <tr>
                            <td class="meta-label">Department</td>
                            <td>{{ $meetingRecord->department ?: 'N/A' }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <table class="summary-table section">
        <tr>
            <td>
                <span class="summary-label">Meeting Type</span>
                <span class="summary-value">{{ $meetingRecord->meeting_type ?: 'N/A' }}</span>
            </td>
            <td>
                <span class="summary-label">Mode</span>
                <span class="summary-value">{{ $meetingRecord->meeting_mode ?: 'N/A' }}</span>
            </td>
            <td>
                <span class="summary-label">Location</span>
                <span class="summary-value">{{ $meetingRecord->meeting_location ?: 'N/A' }}</span>
            </td>
            <td>
                <span class="summary-label">Time Window</span>
                <span class="summary-value">{{ $meetingRecord->start_time ?: 'N/A' }} to {{ $meetingRecord->end_time ?: 'N/A' }}</span>
            </td>
        </tr>
    </table>

    <div class="section">
        <div class="section-title">01 Client And Contact Details</div>
        <table class="fact-grid">
            <tr>
                <td>
                    <span class="fact-label">Client</span>
                    <span class="fact-value">{{ $meetingRecord->client_name ?: optional($meetingRecord->client)->client_name ?: 'N/A' }}</span>
                </td>
                <td>
                    <span class="fact-label">Company Name</span>
                    <span class="fact-value">{{ $meetingRecord->company_name ?: 'N/A' }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="fact-label">Contact Number</span>
                    <span class="fact-value">{{ $meetingRecord->contact_number ?: 'N/A' }}</span>
                </td>
                <td>
                    <span class="fact-label">Contact Person</span>
                    <span class="fact-value">{{ $meetingRecord->contact_person ?: 'N/A' }}</span>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <span class="fact-label">Meeting Attended</span>
                    <span class="fact-value">{{ $meetingRecord->meeting_attended ?: 'N/A' }}</span>
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">02 Attendees</div>
        @if (count($attendees))
            <table class="attendee-list">
                @foreach ($attendeeRows as $row)
                    <tr>
                        @foreach ($row as $index => $attendee)
                            <td>
                                <span class="attendee-index">{{ str_pad((string) (($loop->parent->index * 2) + $index + 1), 2, '0', STR_PAD_LEFT) }}</span>
                                {{ $attendee['employee_name'] ?: 'N/A' }}
                            </td>
                        @endforeach
                        @if (count($row) < 2)
                            <td></td>
                        @endif
                    </tr>
                @endforeach
            </table>
        @else
            <div class="empty">No attendees recorded.</div>
        @endif
    </div>

    <div class="section">
        <div class="section-title">03 Agenda</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 34px;">#</th>
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
                        <td colspan="6" class="empty">No agenda items recorded.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">04 Meeting Summary / Report</div>
        <div class="prose-block">{{ $meetingRecord->discussion_point ?: 'No discussion points recorded.' }}</div>
    </div>

    <div class="section">
        <div class="section-title">05 Follow-Up Action Items</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 34px;">#</th>
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
                        <td colspan="8" class="empty">No action items recorded.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">06 Next Follow-Up</div>
        <table class="fact-grid">
            <tr>
                <td>
                    <span class="fact-label">Next Follow-Up Date</span>
                    <span class="fact-value">{{ optional($meetingRecord->next_follow_up_date)->format('d M Y') ?: 'N/A' }}</span>
                </td>
                <td>
                    <span class="fact-label">Follow-Up Action Summary</span>
                    <span class="fact-value">{{ $meetingRecord->followup_action ?: 'N/A' }}</span>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <span class="fact-label">Next Follow-Up Details</span>
                    <span class="fact-value">{{ $meetingRecord->next_follow_up_details ?: 'N/A' }}</span>
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">07 Attachments</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 34px;">#</th>
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
                        <td colspan="4" class="empty">No attachments configured.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">08 Sign-Off</div>
        <table class="signoff-table">
            <tr>
                <td>
                    <div class="signoff-box">
                        <div class="signoff-label">Prepared By</div>
                        <div class="signoff-line"></div>
                        <div class="signoff-name">{{ optional($meetingRecord->creator)->name ?: 'Authorized Person' }}</div>
                    </div>
                </td>
                <td>
                    <div class="signoff-box">
                        <div class="signoff-label">Reviewed By</div>
                        <div class="signoff-line"></div>
                        <div class="signoff-name">____________________________</div>
                    </div>
                </td>
                <td>
                    <div class="signoff-box">
                        <div class="signoff-label">Approved By</div>
                        <div class="signoff-line"></div>
                        <div class="signoff-name">____________________________</div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer clearfix">
        <div class="footer-left">
            <div><strong>Document Control</strong></div>
            <div>Generated on {{ now()->format('d M Y, h:i A') }}</div>
        </div>
        <div class="footer-right">
            <div>{{ config('app.name') ?: 'CRM' }}</div>
            <div>{{ $reportNumber }}</div>
        </div>
    </div>
</div>
</body>
</html>
