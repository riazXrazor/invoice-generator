<?php

namespace App\Filament\Resources\CompanyDetails\Pages;

use App\Filament\Resources\CompanyDetails\CompanyDetailResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCompanyDetail extends EditRecord
{
    protected static string $resource = CompanyDetailResource::class;

// protected function getHeaderActions(): array
// {
//     return [
//         DeleteAction::make(),
//     ];
// }
}
