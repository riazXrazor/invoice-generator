<?php

namespace App\Http\Controllers;

use App\Models\CompanyDetail;
use App\Models\Invoice;

class PreviewInvoiceController extends Controller
{
    public function __invoke(Invoice $invoice)
    {
        $invoice->load(['client', 'items.product']);
        $company = CompanyDetail::first();
        $template = $company->invoice_template ?? 'invoice_v1';

        return view('pdf.'.$template, ['invoice' => $invoice, 'company' => $company]);
    }
}
