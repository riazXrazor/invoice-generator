<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    public static function shouldRegisterNavigation(): bool
    {
        // Hide the Dashboard from the navigation menu
        return false;
    }

    public function mount()
    {
        // Intercept the landing page and bounce directly to the invoice creator
        return redirect()->to('/admin/invoices/create');
    }
}
