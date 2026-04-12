<?php

namespace App\Filament\Resources\CompanyDetails\Pages;

use App\Filament\Resources\CompanyDetails\CompanyDetailResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCompanyDetails extends ListRecords
{
    protected static string $resource = CompanyDetailResource::class;

// protected function getHeaderActions(): array
// {
//     return [
//         CreateAction::make(),
//     ];
// }
}
