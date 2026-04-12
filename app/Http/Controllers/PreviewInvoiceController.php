<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;

class PreviewInvoiceController extends Controller
{
    public function __invoke(Invoice $invoice)
    {
        $invoice->load(['client', 'items.product']);
        $company = \App\Models\CompanyDetail::first();
        return view('pdf.invoice', ['invoice' => $invoice, 'company' => $company]);
    }
}
