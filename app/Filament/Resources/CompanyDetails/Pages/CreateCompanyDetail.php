<?php

namespace App\Filament\Resources\CompanyDetails\Pages;

use App\Filament\Resources\CompanyDetails\CompanyDetailResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCompanyDetail extends CreateRecord
{
    protected static string $resource = CompanyDetailResource::class;
}
