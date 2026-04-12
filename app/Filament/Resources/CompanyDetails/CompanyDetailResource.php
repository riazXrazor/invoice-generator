<?php

namespace App\Filament\Resources\CompanyDetails;

use App\Filament\Resources\CompanyDetails\Pages\CreateCompanyDetail;
use App\Filament\Resources\CompanyDetails\Pages\EditCompanyDetail;
use App\Filament\Resources\CompanyDetails\Pages\ListCompanyDetails;
use App\Filament\Resources\CompanyDetails\Schemas\CompanyDetailForm;
use App\Filament\Resources\CompanyDetails\Tables\CompanyDetailsTable;
use App\Models\CompanyDetail;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CompanyDetailResource extends Resource
{
    protected static ?string $model = CompanyDetail::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'company_name';
    protected static ?string $navigationLabel = 'Company Settings';
    protected static \UnitEnum|string|null $navigationGroup = 'Settings';
    protected static ?int $navigationSort = 100;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return CompanyDetailForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CompanyDetailsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCompanyDetails::route('/'),
            'create' => CreateCompanyDetail::route('/create'),
            'edit' => EditCompanyDetail::route('/{record}/edit'),
        ];
    }
}
