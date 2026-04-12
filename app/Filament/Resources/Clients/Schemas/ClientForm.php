<?php

namespace App\Filament\Resources\Clients\Schemas;

use Filament\Schemas\Schema;

class ClientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\TextInput::make('name')->required(),
                \Filament\Forms\Components\TextInput::make('gstin')->label('GSTIN'),
                \Filament\Forms\Components\TextInput::make('state')->required(),
                \Filament\Forms\Components\TextInput::make('state_code')->required()->numeric(),
                \Filament\Forms\Components\Textarea::make('address')->required()->columnSpanFull(),
            ]);
    }
}
