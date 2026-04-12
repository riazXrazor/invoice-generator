<?php

namespace App\Filament\Resources\CompanyDetails\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CompanyDetailForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('company_name')
                    ->required()
                    ->default('UNIQUE FOOD PRODUCTS'),
                TextInput::make('address_line_1')
                    ->required()
                    ->default('CHAKARBARIA, KUNDRALI, BARUIPUR, 24 PG(S),'),
                TextInput::make('address_line_2')
                    ->required()
                    ->default('.PIN-746310.'),
                TextInput::make('state')
                    ->required()
                    ->default('WEST BENGAL'),
                TextInput::make('contact_no'),
                TextInput::make('gstin')
                    ->required()
                    ->default('19ACNPL1586D1ZD'),
                TextInput::make('bank_name')
                    ->required()
                    ->default('UNION BANK'),
                TextInput::make('bank_account_no')
                    ->required()
                    ->default('046113100000690'),
                TextInput::make('bank_ifs_code')
                    ->required()
                    ->default('UBIN0804614'),
                TextInput::make('bank_branch')
                    ->required()
                    ->default('KUNDARALI BRANCH'),
                TextInput::make('bank_detail_heading')
                    ->required()
                    ->default('Company\'s Bank Detail: KUNDARALI BRANCH, SOUTH 24 PARGANAS -743302.'),
                Textarea::make('declaration')
                    ->required()
                    ->default("Declare that this invoice shows the actual price of the\nGoods described and that all particular are true & perfect.\nGoods once sold not be taken back.")
                    ->columnSpanFull(),
                TextInput::make('jurisdiction')
                    ->required()
                    ->default('SUBJECT TO BARUIPUR JURISDICTION'),
            ]);
    }
}
