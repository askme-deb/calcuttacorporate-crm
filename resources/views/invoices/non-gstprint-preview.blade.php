<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>INVOICE - Code of Dolphins</title>
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
            padding: 15px;
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
    </style>
</head>

<body>
    <!-- <div style="position: fixed; top: 35%; left: 20%; opacity: 0.1; font-size: 100px; transform: rotate(-30deg); z-index: 0; color:red;">
    PAID
</div> -->
    <div style="position: fixed; top: 25%; left: 25%; width: 50%; opacity: 0.1; z-index: 0;">
        @php
        $image = base64_encode(file_get_contents(public_path('assets/images/invoicelogo.png')));
        @endphp
        <img src="data:image/png;base64,{{ $image }}" style="width: 100%;" alt="Watermark">
    </div>
    <div class="invoice-header">
        <h2>INVOICE</h2>
    </div>
    <table style="border-collapse: collapse; width: 100%; border: #ffffff;">
        <tr>
            <td style="border: none; padding: 5px; vertical-align: top;">
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
                    <!-- GSTIN/UIN: 19AATFC0540J1Z8<br> -->
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
            {{ $invoice->client->company_name ?? $invoice->client->name }}<br>
            Address: {{$invoice->client->address}},{{$invoice->client->state_name}}<br> {{$invoice->client->city}} - {{$invoice->client->pincode}}<br>
            PHONE: {{$invoice->client->phone}}<br>
            Place of Supply: {{$invoice->client->state_name}}
        </div>
        <div style="text-align: right;">
            <strong>Invoice No:</strong> {{$invoice->invoice_number}}<br>
            <strong>Due Date:</strong> {{formatDate($invoice->due_date)}}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Sr. No.</th>
                <th>Name of Product / Service</th>
                <th>Qty</th>
                <th>Rate</th>
                <th>Total Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $key => $item)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $item->description }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->price, 2) }}</td>
                <td>{{ number_format($item->subtotal, 2) }}</td>
            </tr>
            @endforeach

            @for($i=1;$i<=9-count($invoice->items);$i++)
                <tr>
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
            </tr>
            <tr>
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
            </tr>
            <tr>
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
            </tr>
            <tr>
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
            </tr> -->

        </tbody>
        <tfoot>

            @if($invoice->advance_amount > 0)
            <tr style="border-top: 1px solid #000000;">
                <td colspan="4" style="text-align: right;">Advance</td>
                <td><strong>{{ number_format($invoice->advance_amount, 2) }}</strong></td>
            </tr>
            @endif
            <tr style="border-top: 1px solid #000000;">
                <td colspan="4" style="text-align: right;">Payable Amount</td>
                <td>
                    <strong>
                        {{ number_format($invoice->grand_total - ($invoice->advance_amount ?? 0), 2) }}
                    </strong>
                </td>
            </tr>

        </tfoot>
    </table>

    <div class="total-section">
        Amount in Words: <br>
        <strong> {{numberToWordsWithDecimal($invoice->grand_total)}}</strong>
    </div>

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

    <div class="signature">
        For Code of Dolphins<br><br><br>
        Authorised Signatory
    </div>
    <p style="text-align: center;">This is a Computer Generated Invoice</p>
</body>

</html>