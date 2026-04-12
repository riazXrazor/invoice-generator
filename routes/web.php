<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DownloadInvoiceController;

Route::get('/', function () {
    return redirect('/admin');
});

Route::get('/invoice/{invoice}/download', DownloadInvoiceController::class)->name('invoice.download');

Route::get('/invoice/{invoice}/preview', function (App\Models\Invoice $invoice) {
    $invoice->load(['client', 'items.product']);
    return view('pdf.invoice', ['invoice' => $invoice]);
})->name('invoice.preview');
