<?php

namespace App\Filament\Resources\Clients\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ClientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')->required()->columnSpanFull(),
                Select::make('state_code')
                    ->label('State')
                    ->options(\App\Models\State::all()->pluck('name_with_code', 'code'))
                    ->required()
                    ->searchable()->columnSpanFull(),
                TextInput::make('gstin')->label('GSTIN / UTN / PAN')->columnSpanFull(),
                Textarea::make('address')->required()->columnSpanFull(),
            ]);
    }
}
