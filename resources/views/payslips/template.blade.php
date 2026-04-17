<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Payslip - {{ $payroll->employee->full_name }}</title>
    <style>
        @font-face {
            font-family: 'DejaVuSans';
            src: url('https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/fonts/DejaVuSans.ttf') format('truetype');
        }

        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        @page {
            size: A4;
            margin: 10mm;
        }

        body {
            font-family: 'Inter', sans-serif;
            font-size: 9pt;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            width: 100%;
            max-width: 210mm;
            margin: auto;
            background: #fff;
        }

        .header,
        .employee-section,
        .salary-section,
        .summary-section,
        .footer-section {
            padding: 10px 15px;
        }

        .section-title {
            font-size: 10pt;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 8px;
            padding: 6px 10px;
            border-left: 4px solid #2c3e50;
            color: #2c3e50;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5pt;
        }

        td,
        th {
            border: 1px solid #000;
            padding: 6px 8px;
            vertical-align: middle;
        }

        .label {
            font-weight: 600;
            color: #555;
            background: #f9f9f9;
        }

        .amount {
            text-align: right;
            font-weight: 600;
        }

        .total-row td {
            font-weight: 700;
        }

        .summary-table {
            max-width: 350px;
            margin: 0 auto;
        }

        .net-salary-row td {
            font-weight: 700;
            /* border-top: 2px solid #2c3e50; */
            font-size: 10pt;
        }

        .signatures-table td {
            width: 50%;
            text-align: center;
            border: none;
            padding-top: 30px;
        }

        .signature-line {
            border-top: 2px solid #333;
            margin: 15px auto 5px;
            width: 150px;
        }

        .footer-note {
            text-align: center;
            font-size: 7.5pt;
            color: #999;
            margin-top: 15px;
            line-height: 1.4;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>

    <div class="container">

        <!-- Header -->
        <div class="header">
            <table>
                <tr>
                    <td style="width: 100px; text-align:center;border: none;">
                        @php
                        $image = base64_encode(file_get_contents(public_path('assets/images/invoicelogo.png')));
                        @endphp
                        <img src="data:image/png;base64,{{ $image }}" style="width: 100px;">
                    </td>
                    <td style="border: none;">
                        <div style="font-size: 16pt; font-weight: 700; color:#2c3e50;">Code of Dolphins</div>
                        <div style="font-size: 8pt; color:#666;">
                            62/A Selimpur Road, Dhakuria, Kolkata - 700031<br>
                            Phone: +91-8240305245 | Email: hr@codeofdolphins.com
                        </div>
                    </td>
                    <td style="width: 150px; text-align:right;border: none;">
                        <div style="background:#2c3e50;color:white;padding:6px 10px;border-radius:5px;font-weight:600;">
                            Pay Period: {{ \Carbon\Carbon::parse($payroll->pay_period)->format('F Y') }}
                        </div>
                    </td>
                </tr>
            </table>
            <h3 style="text-align:center; margin:8px 0; font-weight:700; color:#2c3e50;">Salary Statement</h3>
        </div>

        <!-- Employee Info -->
        <div class="employee-section">
            <div class="section-title">Employee Information</div>
            <table>
                <tr>
                    <td class="label">Employee Name</td>
                    <td>{{ $payroll->employee->full_name }}</td>
                    <td class="label">Employee ID</td>
                    <td>{{ $payroll->employee->emp_code }}</td>
                </tr>
                <tr>
                    <td class="label">Designation</td>
                    <td>{{ $payroll->employee->designation->name }}</td>
                    <td class="label">Department</td>
                    <td>{{ $payroll->employee->department->name ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Bank Account</td>
                    <td>{{ $payroll->employee->bank_account ?? '-' }}</td>
                    <td class="label">PAN / Tax ID</td>
                    <td>{{ $payroll->employee->emp_pan ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Payment Date</td>
                    <td>{{ \Carbon\Carbon::parse($payroll->updated_at)->format('l, d F Y h:i A') }}</td>
                    <td class="label">Payment Status</td>
                    <td>
                        @if($payroll->is_paid == 1)
                        <span style="color:green;font-weight:600;">PAID</span>
                        @else
                        <span style="color:red;font-weight:600;">UNPAID</span>
                        @endif
                    </td>
                </tr>
            </table>
        </div>

        @php
        $gross = $payroll->items->where('type','!=','deduction')->sum('amount');
        $totalDeductions = $payroll->items->where('type','deduction')->sum('amount');
        $net = $gross - $totalDeductions;
        @endphp

        <!-- Salary Breakdown -->
        <div class="salary-section">
            <div class="section-title">Salary Breakdown</div>
            <table>
                <tr>
                    <!-- Earnings -->
                    <td style="width:50%; vertical-align:top;border: none;">
                        <h3>Earnings</h3>
                        <table>
                            <thead>
                                <tr>
                                    <th>Description</th>
                                    <th style="text-align:right;">Amount (₹)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payroll->items->where('type','!=','deduction') as $item)
                                <tr>
                                    <td>{{ $item->name }}</td>
                                    <td class="amount">{{ number_format($item->amount, 2) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" style="text-align:center;color:#999;font-style:italic;">No Earnings</td>
                                </tr>
                                @endforelse
                                <tr class="total-row">
                                    <td>TOTAL EARNINGS</td>
                                    <td class="amount">{{ number_format($gross, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>

                    <!-- Deductions -->
                    <td style="width:50%; vertical-align:top;border: none;">
                        <h3>Deductions</h3>
                        <table>
                            <thead>
                                <tr>
                                    <th>Description</th>
                                    <th style="text-align:right;">Amount (&#8377;)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payroll->items->where('type','deduction') as $item)
                                <tr>
                                    <td>{{ $item->name }}</td>
                                    <td class="amount">{{ number_format($item->amount, 2) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" style="text-align:center;color:#999;font-style:italic;">No Deductions</td>
                                </tr>
                                @endforelse
                                <tr class="total-row">
                                    <td>TOTAL DEDUCTIONS</td>
                                    <td class="amount">{{ number_format($totalDeductions, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Salary Summary -->
        <div class="summary-section">
            <div class="section-title">Payment Summary</div>
            <table class="summary-table">
                <thead>
                    <tr>
                        <th colspan="2">Salary Summary</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="label">Gross Salary:</td>
                        <td>&#8377; {{ number_format($gross, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="label">Total Deductions:</td>
                        <td>&#8377; {{ number_format($totalDeductions, 2) }}</td>
                    </tr>
                    <tr class="net-salary-row">
                        <td class="label">NET SALARY PAYABLE:</td>
                        <td>₹ {{ number_format($net, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Footer / Signatures -->
        <div class="footer-section">
            <table class="signatures-table">
                <tr>
                    <td>
                        <div class="signature-line"></div>
                        <div style="font-size:9pt; font-weight:600;">Employee Signature</div>
                    </td>
                    <td>
                        <div class="signature-line"></div>
                        <div style="font-size:9pt; font-weight:600;">Authorized Signatory</div>
                    </td>
                </tr>
            </table>
            <div class="footer-note">
                This is a computer-generated document and does not require a physical signature.<br>
                For any discrepancies, contact the HR department within 7 days.
            </div>
        </div>

    </div>
</body>

</html>