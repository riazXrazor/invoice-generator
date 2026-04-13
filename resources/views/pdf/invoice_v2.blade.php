<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ $invoice->invoice_no }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 14px;
            margin: 0;
            padding: 0;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .font-bold {
            font-weight: bold;
        }

        .header {
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .company-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .company-addr {
            font-size: 14px;
        }

        .gstin {
            font-weight: bold;
            font-size: 16px;
            margin-top: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            border: 1px solid #000;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f3f4f6;
        }

        .no-border-bottom td {
            border-bottom: none;
        }

        .no-border-top td {
            border-top: none;
        }

        .flex {
            width: 100%;
            margin-bottom: 20px;
        }

        .col-half {
            width: 50%;
            float: left;
        }

        .col-half-right {
            width: 50%;
            float: right;
            text-align: right;
        }

        .mb-2 {
            margin-bottom: 10px;
        }

        .mt-4 {
            margin-top: 20px;
        }

        .clear {
            clear: both;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 12px;
            font-weight: bold;
        }

        .bank-details {
            width: 60%;
            float: left;
            border: 1px solid #000;
            padding: 10px;
            margin-top: 20px;
        }
    </style>
    <script type="text/javascript">
        @if(request()->has('print'))
            window.onload = function () {
                window.print();
            };
            window.onafterprint = function () {
                window.close();
            };
        @endif
    </script>
</head>

<body>

    @php
        $company = \App\Models\CompanyDetail::first();
    @endphp

    <div class="header text-center">
        <div class="company-name">{{ $company->company_name }}</div>
        <div class="company-addr">{{ $company->address_line_1 }} {{ $company->address_line_2 }} {{ $company->state }}
        </div>
        <div class="gstin">GSTIN: {{ $company->gstin }}</div>
    </div>

    <div class="flex mb-2">
        <div class="col-half" style="width: 40%;">
            <div><strong>Buyer:</strong></div>
            <div>{{ strtoupper($invoice->client->name) }}</div>
            <div>{!! nl2br(e($invoice->client->address)) !!}</div>
            <div><strong>GSTIN:</strong> {{ $invoice->client->gstin ?? 'URP' }}</div>
            <div><strong>State:</strong> {{ strtoupper($invoice->client->state) }} ({{ $invoice->client->state_code }})
            </div>
        </div>
        <div class="col-half" style="width: 35%;">
            <div><strong>Shipping Address:</strong></div>
            @if($invoice->has_different_shipping_address)
                <div>{{ strtoupper($invoice->shipping_name) }}</div>
                <div>{!! nl2br(e($invoice->shipping_address)) !!}</div>
                <div><strong>GSTIN:</strong> {{ $invoice->shipping_gstin ?? 'URP' }}</div>
                <div><strong>State:</strong> {{ strtoupper($invoice->shipping_state) }}
                    ({{ $invoice->shipping_state_code }})</div>
            @else
                <div style="margin-top: 20px;">-DO-</div>
            @endif
        </div>
        <div class="col-half-right" style="width: 30%;">
            <div><strong>Invoice No:</strong> {{ $invoice->invoice_no }}</div>
            <div><strong>Date:</strong> {{ $invoice->invoice_date->format('d-m-Y') }}</div>
            @if($invoice->challan_no)
                <div><strong>Challan No:</strong> {{ $invoice->challan_no }}</div>
            @endif
            @if($invoice->dispatched_through)
                <div><strong>Despatched through:</strong> {{ $invoice->dispatched_through }}</div>
            @endif
        </div>
        <div class="clear"></div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:5%;">Sl No.</th>
                <th style="width:40%;">Description of Goods</th>
                <th style="width:10%;">HSN/SAC</th>
                <th style="width:15%; text-align:center;">Unit</th>
                <th style="width:15%; text-align:center;">Quantity</th>
                <th style="width:15%; text-align:right;">Rate</th>
                <th style="width:15%; text-align:right;">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $index => $item)
                <tr>
                    <td style="text-align:center;">{{ $index + 1 }}</td>
                    <td>{{ $item->product->description }}</td>
                    <td>{{ $item->product->hsn_code }}</td>
                    <td style="text-align:center;">{{ $item->product->unit }}</td>
                    <td style="text-align:center;">{{ (float) $item->quantity }}</td>
                    <td style="text-align:right;">{{ number_format($item->rate, 2) }}</td>
                    <td style="text-align:right;">{{ number_format($item->amount, 2) }}</td>
                </tr>
            @endforeach

            @php
                $isWestBengal = strtoupper(trim($invoice->client->state)) === strtoupper(trim($company->state));
                $cgst = $sgst = $igst = 0;
                if ($isWestBengal) {
                    $cgst = $invoice->tax_amount / 2;
                    $sgst = $invoice->tax_amount / 2;
                } else {
                    $igst = $invoice->tax_amount;
                }
            @endphp

            <tr>
                <td colspan="6" style="text-align:right;"><strong>Total Amount before Tax</strong></td>
                <td style="text-align:right;"><strong>{{ number_format($invoice->subtotal, 2) }}</strong></td>
            </tr>

            @if($isWestBengal)
                <tr>
                    <td colspan="6" style="text-align:right;">Add : CGST @
                        {{ $invoice->items->first()?->product?->tax_rate / 2 ?? 0 }}%
                    </td>
                    <td style="text-align:right;">{{ number_format($cgst, 2) }}</td>
                </tr>
                <tr>
                    <td colspan="6" style="text-align:right;">Add : SGST @
                        {{ $invoice->items->first()?->product?->tax_rate / 2 ?? 0 }}%
                    </td>
                    <td style="text-align:right;">{{ number_format($sgst, 2) }}</td>
                </tr>
            @else
                <tr>
                    <td colspan="6" style="text-align:right;">Add : IGST @
                        {{ $invoice->items->first()?->product?->tax_rate ?? 0 }}%
                    </td>
                    <td style="text-align:right;">{{ number_format($igst, 2) }}</td>
                </tr>
            @endif

            <tr>
                <td colspan="6" style="text-align:right;"><strong>Grand Total (Rounded)</strong></td>
                <td style="text-align:right;"><strong>{{ number_format(round($invoice->grand_total), 2) }}</strong></td>
            </tr>
        </tbody>
    </table>

    <div class="mb-2" style="text-transform: uppercase;">
        <strong>Amount (in words): </strong> <br>
        {{ $invoice->amount_in_words }}
    </div>

    <div class="bank-details">
        <strong>{{ $company->bank_detail_heading }}</strong><br>
        Bank Name : {{ $company->bank_name }}<br>
        A/c No. : {{ $company->bank_account_no }}<br>
        Branch & IFS Code : {{ $company->bank_branch }} & {{ $company->bank_ifs_code }}
    </div>

    <div class="mt-4 text-right" style="float: right;">
        <br><br><br>
        <strong>For {{ $company->company_name }}</strong><br><br>
        <div style="margin-top: 30px;">Authorised Signatory</div>
    </div>

    <div class="clear"></div>

    <div class="footer">
        {{ $company->jurisdiction }}
    </div>

</body>

</html>