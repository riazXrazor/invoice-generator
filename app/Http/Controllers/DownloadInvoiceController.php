<?php

namespace App\Http\Controllers;

use App\Models\CompanyDetail;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;

class DownloadInvoiceController extends Controller
{
    public function __invoke(Invoice $invoice)
    {
        $invoice->load(['client', 'items.product']);
        $company = CompanyDetail::first();
        $template = $company->invoice_template ?? 'invoice_v1';
        $pdf = Pdf::loadView('pdf.'.$template, ['invoice' => $invoice, 'company' => $company]);

        // DOMPDF struggles with large tables sometimes or weird layouts, so we'll use stream for testing.
        return $pdf->stream('invoice-'.str_replace('/', '-', $invoice->invoice_no).'.pdf');
    }
}
