<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('description')->searchable(),
                \Filament\Tables\Columns\TextColumn::make('hsn_code')->searchable(),
                \Filament\Tables\Columns\TextColumn::make('default_rate')->money('INR'),
                \Filament\Tables\Columns\TextColumn::make('tax_rate')->suffix('%'),
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
