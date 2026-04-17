<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>TAX INVOICE - Code of Dolphins</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            font-size: 12px;
        }

        .invoice-header {
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .customer-details,
        .invoice-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            border: 1px solid #000000;
            /* border-top: none;    */
            padding: 15px;
        }

        .section-title {
            font-weight: bold;
            border-bottom: 1px solid black;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 5px;
            text-align: left;
            border-bottom: none;
            border-top: none;
        }

        .total-section {
            margin-top: 20px;
        }

        .terms-conditions {
            margin-top: 20px;
            font-size: 10px;
        }

        .signature {
            margin-top: 10px;
            text-align: right;
        }

        .bank-details {
            margin-top: 20px;
        }


        /* Prevent tables or rows from breaking across pages */
        table,
        tr,
        td,
        th {
            page-break-inside: avoid !important;
            page-break-after: auto;
        }

        .invoice-header,
        .customer-details,
        .invoice-details,
        .total-section,
        .bank-details,
        .signature {
            page-break-inside: avoid !important;
        }
    </style>
</head>
<!-- onload="window.print()" -->

<body>

    <div class="invoice-header">
        <h2>TAX INVOICE</h2>
        <!-- <p>ORIGINAL FOR RECIPIENT</p> -->
    </div>
    <table style="border-collapse: collapse; width: 100%; border: #ffffff;">
        <tr>
            <td style="border: none; padding: 5px;vertical-align: top;">
                @php
                $image = base64_encode(file_get_contents(public_path('assets/images/invoicelogo.png')));
                @endphp

                <img src="data:image/png;base64,{{ $image }}" style="width: 120px;" alt="Logo">


            </td>
            <td style="border: none; padding: 5px;">
                <div>
                    <strong>Code of Dolphins</strong><br>
                    225,Purba Boalia Main Road, Naskar Para, Garia<br>
                    Garia - 700084<br>
                    PHONE: 8240305245<br>
                    GSTIN/UIN: 19AATFC0540J1Z8<br>
                    E-Mail : cod.ac7701@gmail.com

                </div>
            </td>
            <td style="border: none; padding: 5px; text-align: right; vertical-align: top;">
                <strong>Invoice Date:</strong> {{formatDate($invoice->invoice_date)}}<br>
            </td>
        </tr>
    </table>





    <div class="customer-details">
        <div>
            <strong>Customer Detail</strong><br>
            {{$invoice->client->company_name}}<br>
            Address: {{$invoice->client->address}},{{$invoice->client->state_name}}<br> {{$invoice->client->city}} - {{$invoice->client->pincode}}<br>
            PHONE: {{$invoice->client->phone}}<br>
            GSTIN: {{$invoice->client->gst}}<br>
            Place of Supply: {{$invoice->client->state_name}} ( {{$invoice->client->state_code}} )
        </div>
        <div style="text-align: right;">
            <strong>Invoice No:</strong> {{$invoice->invoice_number}}<br><br>
            <!-- <strong>Invoice Date:</strong> 04-Mar-2020<br> -->
            <!-- <strong>Challan No:</strong> 865<br>
            <strong>Challan Date:</strong> 03-Mar-2020<br> -->
            <!-- <strong>P.O. No:</strong> 66<br> -->
            <!-- <strong>Delivery Date:</strong> 04-Mar-2020<br> -->
            <!-- <strong>L.R. No:</strong> 958<br> -->
            <strong>Due Date:</strong> {{formatDate($invoice->due_date)}}<br>
            <!-- <strong>E-Way No:</strong> EWB54864584 -->
        </div>
    </div>

    <table>
        <thead>
            <tr style="border-bottom: 1px solid #000000;">
                <th>Sr. No.</th>
                <th>Name of Product / Service</th>
                <th>HSN / SAC</th>
                <th>Qty</th>
                <th>Rate</th>
                <th>Taxable Value</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $key => $item)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $item->description }}</td>
                <td>998313</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->price, 2) }}</td>
                <td>{{ number_format($item->subtotal, 2) }}</td>
            </tr>
            @endforeach

            @if($invoice->igst_rate!=null || $invoice->igst_rate!=0)
            <tr>
                <td>&nbsp;</td>
                <td style="text-align: right; font-weight:bold;">Output IGST@ {{$invoice->igst_rate}} %</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>{{ number_format($invoice->igst_amount, 2) }}</td>
            </tr>
            @else
            <tr>
                <td>&nbsp;</td>
                <td style="text-align: right; font-weight:bold;">Output CGST@ {{$invoice->cgst_rate}} %</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>{{ number_format($invoice->cgst_amount, 2) }}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td style="text-align: right; font-weight:bold;">Output SGST@ {{$invoice->sgst_rate}} %</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>{{ number_format($invoice->sgst_amount, 2) }}</td>
            </tr>

            @endif
            @for($i=1;$i<=1-count($invoice->items);$i++)
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                @endfor
                <!-- <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr> -->

        </tbody>
        <tfoot>
            <tr style="border-top: 1px solid #000000;">
                <td colspan="5" style="text-align: right;">Total</td>
                <td style="font-weight: bold;">{{ number_format($invoice->grand_total, 2)}}</td>
            </tr>
            <tr style="border-top: 1px solid #000000;">
                <td colspan="5" style="border-right: none;">
                    Amount Chargeable (in words)<br>
                    <strong> {{numberToWordsWithDecimal($invoice->grand_total)}}</strong>
                </td>
                <td style="border-left: none; vertical-align: text-bottom; text-align: right; ">E. &amp; O.E</td>
            </tr>
        </tfoot>

    </table>
<div class="total-section">
    <table style="width:100%; border-collapse: collapse; border:1px solid #000;">

        <!-- Header Row -->
        <tr>
            <th rowspan="2" style="border:1px solid #000; width: 20%;">HSN / SAC</th>
            <th rowspan="2" style="border:1px solid #000; width: 15%;">Taxable Value</th>

            @if($invoice->igst_rate != null && $invoice->igst_rate != 0)
                <th colspan="2" style="border:1px solid #000; text-align:center; width: 25%;">IGST</th>
            @else
                <th colspan="2" style="border:1px solid #000; text-align:center; width: 20%;">CGST</th>
                <th colspan="2" style="border:1px solid #000; text-align:center; width: 20%;">SGST</th>
            @endif

            <th rowspan="2" style="border:1px solid #000; width: 15%;">Total Tax Amount</th>
        </tr>

        <!-- Sub Header Row -->
        <tr>
            <th style="border:1px solid #000;">Rate</th>
            <th style="border:1px solid #000;">Amount</th>

            @if(!($invoice->igst_rate != null && $invoice->igst_rate != 0))
                <th style="border:1px solid #000;">Rate</th>
                <th style="border:1px solid #000;">Amount</th>
            @endif
        </tr>

        <!-- Data Row -->
        <tr>
            <td style="border:1px solid #000;">998313</td>
            <td style="border:1px solid #000;">{{ number_format($invoice->total, 2) }}</td>

            @if($invoice->igst_rate != null && $invoice->igst_rate != 0)
                <td style="border:1px solid #000;">{{ $invoice->igst_rate }}%</td>
                <td style="border:1px solid #000;">{{ number_format($invoice->igst_amount, 2) }}</td>
                <td style="border:1px solid #000;">{{ number_format($invoice->igst_amount, 2) }}</td>
            @else
                <td style="border:1px solid #000;">{{ $invoice->cgst_rate }}%</td>
                <td style="border:1px solid #000;">{{ number_format($invoice->cgst_amount, 2) }}</td>
                <td style="border:1px solid #000;">{{ $invoice->sgst_rate }}%</td>
                <td style="border:1px solid #000;">{{ number_format($invoice->sgst_amount, 2) }}</td>
                <td style="border:1px solid #000;">{{ number_format($invoice->cgst_amount + $invoice->sgst_amount, 2) }}</td>
            @endif
        </tr>

        <!-- Totals Row -->
        <tr>
            <td style="border:1px solid #000; text-align:right;">Total</td>
            <td style="border:1px solid #000;">{{ number_format($invoice->total, 2) }}</td>
            <td style="border:1px solid #000;"></td>

            @if($invoice->igst_rate != null && $invoice->igst_rate != 0)
                <td style="border:1px solid #000;">{{ number_format($invoice->igst_amount, 2) }}</td>
                <td style="border:1px solid #000;">{{ number_format($invoice->igst_amount, 2) }}</td>
            @else
                <td style="border:1px solid #000;">{{ number_format($invoice->cgst_amount, 2) }}</td>
                <td style="border:1px solid #000;"></td>
                <td style="border:1px solid #000;">{{ number_format($invoice->sgst_amount, 2) }}</td>
                <td style="border:1px solid #000;">{{ number_format($invoice->cgst_amount + $invoice->sgst_amount, 2) }}</td>
            @endif
        </tr>

       <!-- Amount in Words Row -->
<!-- Amount in Words Row -->
<tr>

    {{-- LEFT CELL: Tax Amount in words --}}
    <td colspan="{{ ($invoice->igst_rate != null && $invoice->igst_rate != 0) ? 4 : 6 }}"
        style="border:1px solid #000; padding:8px;border-right: none;">

        Tax Amount (in words)<br>

        <strong>
        @if($invoice->igst_rate != null && $invoice->igst_rate != 0)
            {{ numberToWordsWithDecimal($invoice->igst_amount) }}
        @else
            {{ numberToWordsWithDecimal($invoice->cgst_amount + $invoice->sgst_amount) }}
        @endif
        </strong>
    </td>
    <td style="border-left: none; vertical-align: text-bottom; text-align: right; ">E. &amp; O.E</td>

</tr>



    </table>
</div>

    {{-- <div class="total-section">
        <table style="border:1px solid black;">
            <tr style="border-bottom: 1px solid #000000;border: 1px solid #000000;">
                <th rowspan="2" style="width: 40%;">HSN / SAC</th>
                <th rowspan="2" style="width: 12%;">Taxable Value</th>
                @if($invoice->igst_rate!=null || $invoice->igst_rate!=0)
                <th colspan="2" style="text-align: center;width: 20%;">IGST</th>
                @else
                <th colspan="2" style="text-align: center;width: 20%;">CGST</th>
                <th colspan="2" style="text-align: center;width: 20%;">SGST</th>
                @endif
                <th rowspan="2" style="width: 15%;border: 1px solid #000000;">Total Tax Amount</th>
            </tr>
            @if($invoice->igst_rate!=null || $invoice->igst_rate!=0)
            <tr style="border-bottom: 1px solid #000000;">
                <th>Rate</th>
                <th>Amount</th>
            </tr>
            @else
            <tr style="border-bottom: 1px solid #000000;">
                <th>Rate</th>
                <th>Amount</th>
                <th>Rate</th>
                <th>Amount</th>
            </tr>
            @endif
            <tr>
                <td>998313</td>
                <td>{{ number_format($invoice->total, 2)}}</td>
                @if($invoice->igst_rate!=null || $invoice->igst_rate!=0)
                <td>{{ $invoice->igst_rate }}%</td>
                @else
                <td>{{ $invoice->cgst_rate }}%</td>
                @endif
                @if($invoice->igst_rate!=null || $invoice->igst_rate!=0)
                <td>{{ number_format($invoice->igst_amount, 2) }}</td>
                <td>{{ number_format($invoice->igst_amount, 2) }}</td>
                @else
                <td>{{ number_format($invoice->cgst_amount, 2) }}</td>
                <td>{{ $invoice->sgst_rate }}%</td>
                <td>{{ number_format($invoice->sgst_amount, 2) }}</td>
                <td>{{ number_format($invoice->cgst_amount + $invoice->sgst_amount, 2) }}</td>
                @endif
            </tr>
            <tr style="border-top: 1px solid #000000;">

                <td style="text-align: right;">Total</td>
                <td>{{ number_format($invoice->total, 2)}}</td>
                <td></td>
                @if($invoice->igst_rate!=null || $invoice->igst_rate!=0)
                <td>{{ number_format($invoice->igst_amount, 2) }}</td>
                <td>{{ number_format($invoice->igst_amount, 2) }}</td>
                @else
                <td>{{ number_format($invoice->cgst_amount, 2) }}</td>
                <td></td>
                <td>{{ number_format($invoice->sgst_amount, 2) }}</td>
                <td>{{ number_format($invoice->cgst_amount + $invoice->sgst_amount, 2) }}</td>
                @endif
            </tr>
            <tr style="border-top: 1px solid #000000;">
                <td @if($invoice->igst_rate != null && $invoice->igst_rate != 0) colspan="6" @else colspan="7" @endif>
                    Tax Amount (in words) <br>
                    @if($invoice->igst_rate!=null || $invoice->igst_rate!=0)
                    <strong> {{ numberToWordsWithDecimal($invoice->igst_amount) }}</strong>
                    @else
                    <strong> {{ numberToWordsWithDecimal($invoice->cgst_amount + $invoice->sgst_amount) }}</strong>
                    @endif
                </td>

            </tr>
        </table>

    </div> --}}

    <table style="border: 0px;!important;">
        <tr style="border: 0px;!important;">
            <td style="width: 50%;border: 0px;">
                <div class="terms-conditions">
                    <strong>Terms and Conditions</strong>
                    <ol>
                        <li>All services and transactions are subject to West Bengal Jurisdiction.</li>
                        <li>Our responsibility ceases once the service or digital product is delivered.</li>
                        <li>Payments made for services are non-refundable.</li>
                        <li>Delivery of services is provided on an as-is basis, as per the agreed sco</li>
                    </ol>
                    <p>Certified that the particulars given above are true and correct.</p>
                </div>
            </td>
            <td style="border: 0px;!important;">
                <div class="bank-details">
                    <strong>Bank Details</strong><br>
                    @if($invoice->payments->count() > 0)
                    @php $payment = $invoice->payments->first(); @endphp
                    @if($payment->bank)
                    A/c Holder’s Name: {{ $payment->bank->account_holder_name }}<br>
                    Bank Name: {{ $payment->bank->bank_name }}<br>
                    Branch Name: {{ $payment->bank->branch_name }}<br>
                    Bank Account Number: {{ $payment->bank->account_no }}<br>
                    Bank Branch IFSC: {{ $payment->bank->ifsc_code }}
                    @endif
                    @endif
                </div>
            </td>
            <td style="border: 0px;!important;"></td>
        </tr>
    </table>




    <div class="signature">
        for Code of Dolphins<br><br><br>
        Authorised Signatory<br>
        <!-- GSTIN : 24HDE7487RE5RT4 -->
    </div>
    <p style="text-align: center;">This is a Computer Generated Invoice</p>


    <script>
        window.onload = function() {
             window.print();

            // Detect when print dialog is closed
            setTimeout(function() {
                window.onafterprint = function() {
                    window.close();
                };
            }, 500);
        };
    </script>

</body>

</html>
