<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $invoice->invoice_no }}</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; margin: 0; padding: 0; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .header { margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .company-name { font-size: 24px; font-weight: bold; margin-bottom: 5px; }
        .company-addr { font-size: 14px; }
        .gstin { font-weight: bold; font-size: 16px; margin-top: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; border: 1px solid #000; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f3f4f6; }
        .no-border-bottom td { border-bottom: none; }
        .no-border-top td { border-top: none; }
        .flex { width: 100%; margin-bottom: 20px; }
        .col-half { width: 50%; float: left; }
        .col-half-right { width: 50%; float: right; text-align: right; }
        .mb-2 { margin-bottom: 10px; }
        .mt-4 { margin-top: 20px; }
        .clear { clear: both; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 12px; font-weight: bold; }
        .bank-details { width: 60%; float: left; border: 1px solid #000; padding: 10px; margin-top: 20px; }
    </style>
</head>
<body>

<div class="header text-center">
    <div class="company-name">UNIQUE FOOD PRODUCTS</div>
    <div class="company-addr">CHAKARBARIA, KUNDRALI, BARUIPUR, 24 PG(S), WEST BENGAL, PIN-746310</div>
    <div class="gstin">GSTIN: 19ACNPL1586D1ZD</div>
</div>

<div class="flex mb-2">
    <div class="col-half">
        <div><strong>Buyer:</strong></div>
        <div>{{ $invoice->client->name }}</div>
        <div>{!! nl2br(e($invoice->client->address)) !!}</div>
        <div><strong>GSTIN:</strong> {{ $invoice->client->gstin ?? 'URP' }}</div>
        <div><strong>State:</strong> {{ $invoice->client->state }} ({{ $invoice->client->state_code }})</div>
    </div>
    <div class="col-half-right">
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
            <td style="text-align:center;">{{ (float) $item->quantity }} {{ $item->product->unit }}</td>
            <td style="text-align:right;">{{ number_format($item->rate, 2) }}</td>
            <td style="text-align:right;">{{ number_format($item->amount, 2) }}</td>
        </tr>
        @endforeach
        
        @php
            $isWestBengal = strtoupper($invoice->client->state) === 'WEST BENGAL';
            $cgst = $sgst = $igst = 0;
            if ($isWestBengal) {
                $cgst = $invoice->tax_amount / 2;
                $sgst = $invoice->tax_amount / 2;
            } else {
                $igst = $invoice->tax_amount;
            }
        @endphp

        <tr>
            <td colspan="5" style="text-align:right;"><strong>Total Amount before Tax</strong></td>
            <td style="text-align:right;"><strong>{{ number_format($invoice->subtotal, 2) }}</strong></td>
        </tr>

        @if($isWestBengal)
        <tr>
            <td colspan="5" style="text-align:right;">Add : CGST @ {{ $invoice->items->first()?->product?->tax_rate / 2 ?? 0 }}%</td>
            <td style="text-align:right;">{{ number_format($cgst, 2) }}</td>
        </tr>
        <tr>
            <td colspan="5" style="text-align:right;">Add : SGST @ {{ $invoice->items->first()?->product?->tax_rate / 2 ?? 0 }}%</td>
            <td style="text-align:right;">{{ number_format($sgst, 2) }}</td>
        </tr>
        @else
        <tr>
            <td colspan="5" style="text-align:right;">Add : IGST @ {{ $invoice->items->first()?->product?->tax_rate ?? 0 }}%</td>
            <td style="text-align:right;">{{ number_format($igst, 2) }}</td>
        </tr>
        @endif
        
        <tr>
            <td colspan="5" style="text-align:right;"><strong>Grand Total (Rounded)</strong></td>
            <td style="text-align:right;"><strong>{{ number_format(round($invoice->grand_total), 2) }}</strong></td>
        </tr>
    </tbody>
</table>

<div class="mb-2" style="text-transform: uppercase;">
    <strong>Amount (in words): </strong> <br>
    {{ $invoice->amount_in_words }}
</div>

<div class="bank-details">
    <strong>Company's Bank Details</strong><br>
    Bank Name : UNION BANK<br>
    A/c No. : 046113100000690<br>
    Branch & IFS Code : KUNDARALI & UBIN0804614
</div>

<div class="mt-4 text-right" style="float: right;">
    <br><br><br>
    <strong>For UNIQUE FOOD PRODUCTS</strong><br><br>
    <div style="margin-top: 30px;">Authorised Signatory</div>
</div>

<div class="clear"></div>

<div class="footer">
    SUBJECT TO BARUIPUR JURISDICTION
</div>

</body>
</html>
