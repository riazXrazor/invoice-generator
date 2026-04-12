<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\TextInput::make('description')->required(),
                \Filament\Forms\Components\TextInput::make('hsn_code')->required()->label('HSN Code'),
                \Filament\Forms\Components\TextInput::make('unit')->required(),
                \Filament\Forms\Components\TextInput::make('default_rate')->numeric()->required(),
                \Filament\Forms\Components\Select::make('tax_rate')
                    ->options([
                        '0' => '0%',
                        '5' => '5%',
                        '12' => '12%',
                        '18' => '18%',
                        '28' => '28%',
                    ])
                    ->required(),
            ]);
    }
}
