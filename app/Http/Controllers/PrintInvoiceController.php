<?php

namespace App\Http\Controllers;

use App\Models\CompanyDetail;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;

class PrintInvoiceController extends Controller
{
    public function __invoke(Invoice $invoice)
    {
        $invoice->load(['client', 'items.product']);
        $company = CompanyDetail::first();

        // Generate the DOMPDF binary with JS enabled for auto-print
        $template = $company->invoice_template ?? 'invoice_v1';
        $pdf = Pdf::setOptions(['isJavascriptEnabled' => true])
            ->loadView('pdf.'.$template, ['invoice' => $invoice, 'company' => $company]);

        $base64 = base64_encode($pdf->output());

        // Return a raw HTML wrapper around a base64 Data URI
        // IDM cannot intercept Data URIs because no secondary network request is made.
        return response(<<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Print Invoice - {$invoice->invoice_no}</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/print-js/1.6.0/print.min.js"></script>
</head>
<body style="margin: 0; padding: 0; background-color: #525659; display: flex; align-items: center; justify-content: center; height: 100vh; color: white; font-family: sans-serif;">
    <h3>Preparing Print Dialog...</h3>
    <script>
        window.onload = function() {
            printJS({
                printable: '{$base64}', 
                type: 'pdf', 
                base64: true,
                onPrintDialogClose: function() {
                    window.close();
                }
            });
        };
    </script>
</body>
</html>
HTML
        );
    }
}
