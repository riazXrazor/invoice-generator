<?php

namespace App\Filament\Resources\CompanyDetails\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CompanyDetailsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('company_name')
                    ->searchable(),
                TextColumn::make('address_line_1')
                    ->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('address_line_2')
                    ->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('state')
                    ->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('contact_no')
                    ->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('gstin')
                    ->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('bank_name')
                    ->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('bank_account_no')
                    ->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('bank_ifs_code')
                    ->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('bank_branch')
                    ->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('bank_detail_heading')
                    ->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('jurisdiction')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('invoice_prefix')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
