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
                    ->searchable(),
                TextColumn::make('address_line_2')
                    ->searchable(),
                TextColumn::make('state')
                    ->searchable(),
                TextColumn::make('contact_no')
                    ->searchable(),
                TextColumn::make('gstin')
                    ->searchable(),
                TextColumn::make('bank_name')
                    ->searchable(),
                TextColumn::make('bank_account_no')
                    ->searchable(),
                TextColumn::make('bank_ifs_code')
                    ->searchable(),
                TextColumn::make('bank_branch')
                    ->searchable(),
                TextColumn::make('bank_detail_heading')
                    ->searchable(),
                TextColumn::make('jurisdiction')
                    ->searchable(),
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
