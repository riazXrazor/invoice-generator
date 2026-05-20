<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ $invoice->invoice_no }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 13px;
            margin: 0;
            padding: 0;
            color: #000;
        }

        table.main-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000;
        }

        table.main-table>tbody>tr>th,
        table.main-table>tbody>tr>td,
        table.main-table>thead>tr>th {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: top;
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

        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #1e4d8c;
            margin-bottom: 2px;
        }

        .no-border-left {
            border-left: none !important;
        }

        .no-border-right {
            border-right: none !important;
        }

        .no-border-top {
            border-top: none !important;
        }

        .no-border-bottom {
            border-bottom: none !important;
        }

        .item-data td {
            border-bottom: none !important;
            border-top: none !important;
            padding-top: 10px;
        }

        .empty-height td {
            border-bottom: none !important;
            border-top: none !important;
            height: 130px;
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
@php
    $isIntraState = ($invoice->shipping_state_code ?? $invoice->client->state_code) === $company->state_code;
@endphp

<body>
    <table class="main-table">
        <tbody>
            <!-- First Block: Company Info & Invoice Info -->
            <tr>
                <td colspan="8" style="padding: 0; border-bottom: 1px solid #000;">
                    <table style="width: 100%; border-collapse: collapse; border: none;">
                        <tr>
                            <td rowspan="2"
                                style="width: 50%; border: none; border-right: 1px solid #000; padding: 10px;">
                                <div class="company-name">{{ $company->company_name }}</div>
                                <div>{{ $company->address_line_1 }}</div>
                                <div>{{ $company->address_line_2 }}</div>
                                <div>{{ $company->state }}</div>
                                <div>Contact no :- {{ $company->contact_no }}</div>
                                <div class="font-bold">GSTIN/UID- {{ $company->gstin }}</div>
                            </td>
                            <td
                                style="width: 25%; border: none; border-right: 1px solid #000; border-bottom: 1px solid #000; padding: 5px;vertical-align: top;">
                                Invoice No: {{ $invoice->invoice_no }}
                            </td>
                            <td
                                style="width: 25%; border: none; border-bottom: 1px solid #000; padding: 5px;vertical-align: top;">
                                Dated: {{ $invoice->invoice_date->format('d/m/Y') }}.
                            </td>
                        </tr>
                        <tr>
                            <td style="border: none; border-right: 1px solid #000; padding: 5px;vertical-align: top;">
                                Challan No: {{ $invoice->challan_no }}
                            </td>
                            <td style="border: none; padding: 5px;vertical-align: top;">
                                Dispatched Through: {{ $invoice->dispatched_through }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <!-- Second Block: Buyer & Shipping Info -->
            <tr>
                <td colspan="8" style="padding: 0; border-bottom: 1px solid #000;">
                    <table style="width: 100%; border-collapse: collapse; border: none;">
                        <tr>
                            <td
                                style="width: 50%; border: none; border-right: 1px solid #000; padding: 5px; vertical-align: top;">
                                <div style="margin-bottom: 5px; font-size: 14px;"><strong>Buyer :-
                                        {{ strtoupper($invoice->client->name) }}.</strong></div>
                                <div>Adress- {!! nl2br(e($invoice->client->address)) !!}</div>
                                <br><br>
                                @if(!$isIntraState)
                                    <div><strong>GSTIN/UTN:- {{ $invoice->client->gstin }}</strong></div>
                                    <br>
                                @else
                                    <div><strong>GSTIN/UTN/PAN NO:- {{ $invoice->client->gstin }}</strong></div>
                                    <br>
                                @endif
                                <table style="width: 100%; border: none; font-size: 13px;">
                                    <tr>
                                        <td style="border: none; padding: 0;">State:
                                            {{ strtoupper($invoice->client->state) }}
                                        </td>
                                        <td style="border: none; padding: 0; text-align: right;">State Code:
                                            {{ $invoice->client->state_code }}
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td style="width: 50%; border: none; padding: 0; vertical-align: top;">
                                <div style="border-bottom: 1px solid #000; padding: 5px;">
                                    <strong>Shipping Address:-</strong>
                                </div>

                                @if($invoice->has_different_shipping_address)
                                    <div style="padding: 5px;">
                                        <div style="margin-bottom: 5px; font-size: 14px;">
                                            <strong>{{ strtoupper($invoice->shipping_name) }}</strong>
                                        </div>
                                        <div>{!! nl2br(e($invoice->shipping_address)) !!}</div>
                                        <br>
                                        <div><strong>GSTIN/UTN:- {{ $invoice->shipping_gstin }}</strong></div>
                                        <table style="width: 100%; border: none; font-size: 13px; margin-top: 5px;">
                                            <tr>
                                                <td style="border: none; padding: 0;">State:
                                                    {{ strtoupper($invoice->shipping_state) }}
                                                </td>
                                                <td style="border: none; padding: 0; text-align: right;">State Code:
                                                    {{ $invoice->shipping_state_code }}
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                @else
                                    <div style="text-align: center; margin-top: 30px;">-DO-</div>
                                    <br><br>
                                    <div style="padding: 0 5px;">GSTIN/UTN:- </div>
                                    <table
                                        style="width: 100%; border: none; font-size: 13px; margin-top: 5px; padding: 0 5px;">
                                        <tr>
                                            <td style="border: none; padding: 0;">State: </td>
                                            <td style="border: none; padding: 0; text-align: right;">State Code: </td>
                                        </tr>
                                    </table>
                                @endif
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <!-- Table Headers -->

                <th style="width:5%; text-align:center; font-weight:normal;font-weight:bold;vertical-align: middle;">
                    SL<br>No</th>
                <th style="width:30.5%; text-align:center; font-weight:normal;font-weight:bold;vertical-align: middle;">
                    Description of Goods
                </th>
                <th style="width:10.5%; text-align:center; font-weight:normal;font-weight:bold;vertical-align: middle;">
                    HSN Code</th>
                <th style="width:6%; text-align:center; font-weight:normal;font-weight:bold;vertical-align: middle;">
                    Unit</th>
                <th style="width:6%; text-align:center; font-weight:normal;font-weight:bold;vertical-align: middle;">Qty
                </th>
                <th style="width:12%; text-align:center; font-weight:normal;font-weight:bold;vertical-align: middle;">
                    Rate</th>
                <th style="width:10%; text-align:center; font-weight:normal;font-weight:bold;vertical-align: middle;">
                    Tax %</th>
                <th style="width:20%; text-align:center; font-weight:normal;font-weight:bold;vertical-align: middle;">
                    Amount</th>
            </tr>

            <!-- Items -->
            @foreach($invoice->items as $index => $item)
                <tr class="item-data">
                    <td class="text-center">{{ $index + 1 }}.</td>
                    <td>{{ strtoupper($item->product->description) }}</td>
                    <td class="text-center">{{ $item->product->hsn_code }}</td>
                    <td class="text-center">{{ strtoupper($item->product->unit) }}</td>
                    <td class="text-center">{{ (float) $item->quantity }}</td>
                    <td class="text-center">{{ number_format($item->rate, 2) }}</td>
                    <td class="text-center">{{ (float) $item->tax_rate }}%</td>
                    <td class="text-center">{{ number_format($item->amount, 2) }}</td>
                </tr>
            @endforeach

            @php

                $cgst = $sgst = $igst = 0;
                if ($isIntraState) {
                    $cgst = $invoice->tax_amount / 2;
                    $sgst = $invoice->tax_amount / 2;
                } else {
                    $igst = $invoice->tax_amount;
                }

                $grandTotalExact = $invoice->subtotal + $invoice->tax_amount;
                $grandTotalRounded = round($grandTotalExact);
                $roundOff = $grandTotalRounded - $grandTotalExact;
            @endphp

            <!-- Empty rows logic mapping -->
            <tr class="empty-height">
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>

            <tr>
                <td class="no-border-top no-border-bottom"></td>
                <td class="no-border-top no-border-bottom"></td>
                <td class="no-border-top no-border-bottom"></td>
                <td class="no-border-top no-border-bottom"></td>
                <td class="no-border-top no-border-bottom"></td>
                <td class="no-border-top no-border-bottom"></td>
                <td style="border-top: 1px solid #000; border-bottom: none; border-top: none; padding-bottom:5px;">
                    IGST</td>
                <td style="border-top: 1px solid #000; border-bottom: none; text-align:center;">
                    {{ number_format($igst, 2) }}
                </td>
            </tr>
            @if($isIntraState)
                <tr>
                    <td class="no-border-top no-border-bottom"></td>
                    <td class="no-border-top no-border-bottom"></td>
                    <td class="no-border-top no-border-bottom"></td>
                    <td class="no-border-top no-border-bottom"></td>
                    <td class="no-border-top no-border-bottom"></td>
                    <td class="no-border-top no-border-bottom"></td>
                    <td style="border-bottom: none; border-top: none; padding-bottom:5px;">
                        CGST</td>
                    <td style="border-bottom: none; border-top: none; text-align:center;">
                        {{ number_format($cgst, 2) }}
                    </td>
                </tr>
                <tr>
                    <td class="no-border-top no-border-bottom"></td>
                    <td class="no-border-top no-border-bottom"></td>
                    <td class="no-border-top no-border-bottom"></td>
                    <td class="no-border-top no-border-bottom"></td>
                    <td class="no-border-top no-border-bottom"></td>
                    <td class="no-border-top no-border-bottom"></td>
                    <td style="border-bottom: none; border-top: none;">
                        SGST</td>
                    <td style="border-bottom: none; border-top: none; text-align:center;">{{ number_format($sgst, 2) }}</td>
                </tr>
            @endif

            <tr>
                <td class="no-border-top no-border-bottom"></td>
                <td class="no-border-top no-border-bottom"></td>
                <td class="no-border-top no-border-bottom"></td>
                <td class="no-border-top no-border-bottom"></td>
                <td class="no-border-top no-border-bottom"></td>
                <td class="no-border-top no-border-bottom"></td>
                <td style="border-bottom: none; border-top: none; padding-top:15px; padding-bottom:15px;">Round off</td>
                <td
                    style="border-bottom: none; border-top: none; text-align:center; padding-top:15px; padding-bottom:15px;">
                    {{ number_format($roundOff, 2) }}
                </td>
            </tr>

            <!-- Total Row -->
            <tr>
                <th class="text-center"></th>
                <th style="text-align: left; font-weight: bold;">Total.</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th class="text-center" style="font-weight: bold;">{{ number_format(round($invoice->grand_total), 2) }}
                </th>
            </tr>

            <!-- Amount In Words -->
            <tr>
                <td colspan="8" style="padding: 5px;">
                    <strong>Amount (in word) : {{ $invoice->amount_in_words }}.</strong>
                </td>
            </tr>

            <!-- Bank Details and Declaration -->
            <tr>
                <td colspan="8" style="padding: 10px;padding-bottom: 0;padding-right: 0;">
                    <div style="font-weight: bold;">{{ $company->bank_detail_heading }}</div>
                    <table style="width: 60%; border: none; font-size: 13px; margin-top: 5px;">
                        <tr>
                            <td style="border: none; padding: 1px 0; width: 100px;">Bank Name</td>
                            <td style="border: none; padding: 1px 0;">: {{ $company->bank_name }}</td>
                        </tr>
                        <tr>
                            <td style="border: none; padding: 1px 0;">A/C No</td>
                            <td style="border: none; padding: 1px 0;">: {{ $company->bank_account_no }}</td>
                        </tr>
                        <tr>
                            <td colspan="2" style="border: none; padding: 5px 0;"></td>
                        </tr>
                        <tr>
                            <td style="border: none; padding: 1px 0;">IFS Code</td>
                            <td style="border: none; padding: 1px 0;">: {{ $company->bank_ifs_code }}</td>
                        </tr>
                        <tr>
                            <td style="border: none; padding: 1px 0;">Branch :</td>
                            <td style="border: none; padding: 1px 0;">: {{ $company->bank_branch }}</td>
                        </tr>
                    </table>

                    <div style="margin-top: 15px; width: 100%;">
                        <table style="width: 100%;border: none;border-spacing: 0;">
                            <tr>
                                <td style="border: none; padding: 0; width: 60%; vertical-align: top;">
                                    <div style="font-weight: bold; margin-bottom: 3px;">Declaration :</div>
                                    <div>{!! nl2br(e($company->declaration)) !!}</div>
                                </td>
                                <td
                                    style="border: none;padding: 0;width: 40%;vertical-align: bottom;text-align: right;border: 1px solid #000;border-bottom: 0;border-right: 0;">
                                    <div style="">
                                        <div style="text-align: left; font-size: 13px; padding: 5px;">For</div>
                                        <div style="margin-top: 40px; width: 100%; text-align: center;">Authorised
                                            signatory</div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>

    <div style="text-align: center; margin-top: 20px; font-weight: bold;">
        {{ $company->jurisdiction }}
    </div>

</body>

</html>