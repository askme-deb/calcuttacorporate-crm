<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Payslip - Employee Name</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        @page {
            size: A4;
            margin: 10mm;
        }

        body {
            font-family: 'Inter', 'Helvetica Neue', Arial, sans-serif;
            font-size: 9pt;
            line-height: 1.3;
            color: #333;
            margin: 0;
            padding: 0;
            font-weight: 500;
            /* Glossy/bold appearance */
        }

        .container {
            width: 100%;
            max-width: 210mm;
            margin: 0 auto;
            /* border: 1px solid #000; */
            background: white;
        }

        /* Header Section */
        .header {
            /* border-bottom: 1px solid #000; */
            padding: 8px 15px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            vertical-align: top;
            padding: 5px;
        }

        .company-logo {
            width: 50px;
            height: 50px;
            /* border: 1px solid #000; */
            text-align: center;
            vertical-align: middle;
            font-weight: 700;
            border-radius: 5px;
            background: white;
        }

        .company-info {
            padding-left: 15px;
        }

        .company-name {
            font-size: 16pt;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 5px;
            /* text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1); */
            /* Glossy effect */
        }

        .company-address {
            font-size: 8pt;
            color: #666;
            line-height: 1.3;
            font-weight: 500;
        }

        .pay-period {
            background: #2c3e50;
            color: white;
            padding: 8px 12px;
            border-radius: 5px;
            font-size: 9pt;
            font-weight: 600;
            text-align: center;
            border: 1px solid #000;
        }

        .payslip-title {
            text-align: center;
            font-size: 12pt;
            font-weight: 700;
            text-transform: uppercase;
            border: 1px solid #000;
            padding: 8px;
            margin-top: 8px;
            color: #2c3e50;
            background: white;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        /* Employee Details Section */
        .employee-section {
            padding: 10px 15px;
            */
        }

        .section-title {
            font-size: 10pt;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 8px;
            padding: 6px 10px;
            border-left: 4px solid #2c3e50;
            color: #2c3e50;
            background: white;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5pt;
            font-weight: 500;
        }

        .details-table td {
            padding: 6px 10px;
            border: 1px solid #000;
            vertical-align: middle;
        }

        .details-table .label {
            font-weight: 600;
            width: 25%;
            color: #555;
            background: white;
        }

        /* Salary Breakdown Section - Using Table Layout for PDF Compatibility */
        .salary-section {
            padding: 10px 15px;
        }

        .salary-breakdown-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        .salary-breakdown-table td {
            width: 50%;
            vertical-align: top;
            padding: 0 5px;
        }

        .salary-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000;
            font-size: 8.5pt;
            font-weight: 500;
        }

        .salary-table th {
            /* background: #2c3e50; */
            color: black;
            padding: 8px 6px;
            text-align: left;
            font-weight: 600;
            text-transform: uppercase;
            border: 1px solid #000;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
        }

        .salary-table td {
            padding: 5px 8px;
            border: 1px solid #000;
        }

        .salary-table .amount {
            text-align: right;
            font-family: 'Inter', 'Courier New', monospace;
            font-weight: 600;
        }

        .salary-table .total-row {
            font-weight: 700;
            background: white;
        }

        .salary-table .total-row td {
            border-top: 1px solid #000;
            padding: 8px;
        }

        .empty-deduction {
            text-align: center;
            font-style: italic;
            color: #999;
            padding: 10px;
            font-weight: 500;
        }

        /* Summary Section */
        .summary-section {
            padding: 10px 15px;
            /* border-top: 1px solid #000; */
        }

        .summary-table {
            width: 100%;
            max-width: 350px;
            margin: 0 auto;
            border-collapse: collapse;
            border: 1px solid #000;
            font-size: 9pt;
            font-weight: 500;
        }

        .summary-table th {
            /* background: #2c3e50; */
            color: black;
            padding: 8px;
            text-align: center;
            font-weight: 600;
            text-transform: uppercase;
            border: 1px solid #000;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
        }

        .summary-table td {
            padding: 8px 10px;
            border: 1px solid #000;
            text-align: right;
        }

        .summary-table .label {
            text-align: left;
            color: #555;
            font-weight: 600;
        }

        .net-salary-row {
            font-weight: 700;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }

        .net-salary-row td {
            border-top: 2px solid #2c3e50;
            font-size: 10pt;
        }

        /* Footer Section - Using Table Layout */
        .footer-section {
            padding: 15px;
            border-top: 2px solid #e0e0e0;
        }

        .signatures-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .signatures-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            padding: 0 20px;
        }

        .signature-block {
            text-align: left;
        }
        .signature-blockright {
            text-align: right;
        }
        .signature-line {
            border-top: 2px solid #333;
            margin: 15px 0 5px;
            width: 150px;
            /* margin-left: auto; */
            /* margin-right: auto; */
        }
        .signature-lineright {
            border-top: 2px solid #333;
            margin: 15px 0 5px;
            width: 150px;
            margin-left: auto;
            /* margin-right: auto; */
        }
        .signature-label {
            font-size: 9pt;
            font-weight: 600;
            text-transform: uppercase;
            color: #666;
        }

        .date-field {
            font-size: 8pt;
            color: #666;
            margin-bottom: 10px;
            font-weight: 500;
        }

        .footer-note {
            text-align: center;
            font-size: 7.5pt;
            color: #999;
            margin-top: 15px;
            line-height: 1.4;
            font-weight: 500;
        }

        /* Enhanced Glossy Effects */
        .glossy-text {
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
        }

        .glossy-bg {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2);
        }

        /* Print Styles */
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .salary-breakdown-table,
            .signatures-table,
            .header-table {
                page-break-inside: avoid;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <table class="header-table">
                <tr>
                    <td style="width: 100px;">
                        <div class="company-logo glossy-bg">
                            @php
                            $image = base64_encode(file_get_contents(public_path('assets/images/invoicelogo.png')));
                            @endphp
                            <img src="data:image/png;base64,{{ $image }}" style="width: 120px;" alt="Logo">

                        </div>
                    </td>
                    <td>
                        <div class="company-info">
                            <div class="company-name glossy-text">Code of Dolphins</div>
                            <div class="company-address">
                                62/A Selimpur Road, Dhakuria, Kolkata - 700031<br>
                                Phone: +91-8240305245 | Email: hr@codeofdolphins.com <br /> Web: www.codeofdolphins.com
                            </div>
                        </div>
                    </td>
                    <td style="width: 150px; text-align: right;">
                        <div class="pay-period">
                            Pay Period: March 2024
                        </div>
                    </td>
                </tr>
            </table>
            <div class="payslip-title glossy-bg glossy-text">
                Salary Statement
            </div>
        </div>

        <!-- Employee Details -->
        <div class="employee-section">
            <div class="section-title glossy-bg glossy-text">Employee Information</div>
            <table class="details-table">
                <tr>
                    <td class="label glossy-bg">Employee Name:</td>
                    <td class="value">John Doe</td>
                    <td class="label glossy-bg">Employee ID:</td>
                    <td class="value">EMP001</td>
                </tr>
                <tr>
                    <td class="label glossy-bg">Designation:</td>
                    <td class="value">Software Developer</td>
                    <td class="label glossy-bg">Department:</td>
                    <td class="value">IT Department</td>
                </tr>
                <tr>
                    <td class="label glossy-bg">Bank Account:</td>
                    <td class="value">1234567890</td>
                    <td class="label glossy-bg">PAN / Tax ID:</td>
                    <td class="value">ABCDE1234F</td>
                </tr>
                <tr>
                    <td class="label glossy-bg">Payment Date:</td>
                    <td class="value">31 Mar 2024</td>
                    <td class="label glossy-bg">Payment Status:</td>
                    <td class="value">
                        <span style="color: #28a745; font-weight: 600;">PAID</span>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Salary Breakdown -->
        <div class="salary-section">
            <div class="section-title glossy-bg glossy-text">Salary Breakdown</div>
            <table class="salary-breakdown-table">
                <tr>
                    <td>
                        <!-- Earnings -->
                        <table class="salary-table">
                            <thead>
                                <tr>
                                    <th colspan="2">Earnings</th>
                                </tr>
                                <tr>
                                    <th>Description</th>
                                    <th style="text-align: right;">Amount (₹)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Basic Salary</td>
                                    <td class="amount">45,000.00</td>
                                </tr>
                                <tr>
                                    <td>House Rent Allowance</td>
                                    <td class="amount">18,000.00</td>
                                </tr>
                                <tr>
                                    <td>Transport Allowance</td>
                                    <td class="amount">3,000.00</td>
                                </tr>
                                <tr>
                                    <td>Medical Allowance</td>
                                    <td class="amount">2,000.00</td>
                                </tr>
                                <tr>
                                    <td>Performance Bonus</td>
                                    <td class="amount">5,000.00</td>
                                </tr>
                                <tr class="total-row glossy-bg">
                                    <td><strong>TOTAL EARNINGS</strong></td>
                                    <td class="amount"><strong>73,000.00</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td>
                        <!-- Deductions -->
                        <table class="salary-table">
                            <thead>
                                <tr>
                                    <th colspan="2">Deductions</th>
                                </tr>
                                <tr>
                                    <th>Description</th>
                                    <th style="text-align: right;">Amount (₹)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Provident Fund</td>
                                    <td class="amount">5,400.00</td>
                                </tr>
                                <tr>
                                    <td>Professional Tax</td>
                                    <td class="amount">200.00</td>
                                </tr>
                                <tr>
                                    <td>Income Tax</td>
                                    <td class="amount">3,500.00</td>
                                </tr>
                                <tr>
                                    <td>Health Insurance</td>
                                    <td class="amount">1,200.00</td>
                                </tr>
                                <tr>
                                    <td style="color: transparent;">-</td>
                                    <td class="amount" style="color: transparent;">0.00</td>
                                </tr>
                                <tr class="total-row glossy-bg">
                                    <td><strong>TOTAL DEDUCTIONS</strong></td>
                                    <td class="amount"><strong>10,300.00</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Summary -->
        <div class="summary-section">
            <div class="section-title glossy-bg glossy-text">Payment Summary</div>
            <table class="summary-table">
                <thead>
                    <tr>
                        <th colspan="2">Salary Summary</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="label">Gross Salary:</td>
                        <td>₹ 73,000.00</td>
                    </tr>
                    <tr>
                        <td class="label">Total Deductions:</td>
                        <td>₹ 10,300.00</td>
                    </tr>
                    <tr class="net-salary-row glossy-bg">
                        <td class="label"><strong>NET SALARY PAYABLE:</strong></td>
                        <td><strong>₹ 62,700.00</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div class="footer-section">
            <table class="signatures-table">
                <tr>
                    <td>
                        <div class="signature-block">
                            <div class="date-field">Date: ___________________</div>
                            <div class="signature-line"></div>
                            <div class="signature-label">Employee Signature</div>
                        </div>
                    </td>
                    <td>
                        <div class="signature-blockright">
                            <div class="date-field">Date: ___________________</div>
                            <div class="signature-lineright"></div>
                            <div class="signature-label">Authorized Signatory</div>
                        </div>
                    </td>
                </tr>
            </table>
            <div class="footer-note">
                This is a computer-generated document and does not require a physical signature.<br>
                For any discrepancies or queries regarding this payslip, please contact the HR department within 7 days of receipt.
            </div>
        </div>
    </div>
</body>

</html>