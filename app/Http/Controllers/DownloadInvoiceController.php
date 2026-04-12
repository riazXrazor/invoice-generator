<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;

class DownloadInvoiceController extends Controller
{
    public function __invoke(Invoice $invoice)
    {
        $invoice->load(['client', 'items.product']);
        $company = \App\Models\CompanyDetail::first();
        $pdf = Pdf::loadView('pdf.invoice', ['invoice' => $invoice, '' => $company]);

        // DOMPDF struggles with large tables sometimes or weird layouts, so we'll use stream for testing.
        return $pdf->stream('invoice-' . str_replace('/', '-', $invoice->invoice_no) . '.pdf');
    }
}
