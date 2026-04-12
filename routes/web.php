<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DownloadInvoiceController;
use App\Http\Controllers\PreviewInvoiceController;
use App\Http\Controllers\PrintInvoiceController;

Route::get('/', function () {
    return redirect('/admin');
});

Route::get('/invoice/{invoice}/download', DownloadInvoiceController::class)->name('invoice.download');

Route::get('/invoice/{invoice}/preview', PreviewInvoiceController::class)->name('invoice.preview');

Route::get('/invoice/{invoice}/print', PrintInvoiceController::class)->name('invoice.print');
