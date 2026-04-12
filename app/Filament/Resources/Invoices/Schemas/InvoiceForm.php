<?php

namespace App\Filament\Resources\Invoices\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use App\Models\Product;
use App\Models\Client;

class InvoiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Invoice Details')
                    ->columns(2)
                    ->schema([
                        Select::make('client_id')
                            ->relationship('client', 'name')
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn(Set $set, Get $get) => self::updateTotals($set, $get)),
                        TextInput::make('invoice_no')
                            ->default(fn() => \App\Models\CompanyDetail::first()->invoice_prefix . '/' . str_pad(\App\Models\Invoice::count() + 1, 3, '0', STR_PAD_LEFT) . '/' . ((date('y')) . '-' . (date('y') + 1)))
                            ->required()
                            ->unique(ignoreRecord: true),
                        DatePicker::make('invoice_date')
                            ->default(now())
                            ->required(),
                        TextInput::make('challan_no'),
                        TextInput::make('dispatched_through'),
                    ]),
                Section::make('Items')
                    ->schema([
                        Repeater::make('items')
                            ->relationship()
                            ->columns(4)
                            ->live()
                            ->afterStateUpdated(fn(Set $set, Get $get) => self::updateTotals($set, $get))
                            ->schema([
                                Select::make('product_id')
                                    ->relationship('product', 'description')
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                        $product = Product::find($state);
                                        if ($product) {
                                            $set('rate', $product->default_rate);
                                        }
                                        $qty = (float) $get('quantity') ?: 1;
                                        $rate = (float) $get('rate') ?: 0;
                                        $set('amount', $qty * $rate);
                                    }),
                                TextInput::make('quantity')
                                    ->numeric()
                                    ->default(1)
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function (Set $set, Get $get) {
                                        $qty = (float) $get('quantity') ?: 1;
                                        $rate = (float) $get('rate') ?: 0;
                                        $set('amount', $qty * $rate);
                                    }),
                                TextInput::make('rate')
                                    ->numeric()
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function (Set $set, Get $get) {
                                        $qty = (float) $get('quantity') ?: 1;
                                        $rate = (float) $get('rate') ?: 0;
                                        $set('amount', $qty * $rate);
                                    }),
                                TextInput::make('amount')
                                    ->numeric()
                                    ->required()
                                    ->readOnly(),
                            ])
                    ]),
                Section::make('Totals')
                    ->columns(3)
                    ->schema([
                        TextInput::make('subtotal')
                            ->numeric()
                            ->readOnly(),
                        TextInput::make('tax_amount')
                            ->numeric()
                            ->readOnly(),
                        TextInput::make('grand_total')
                            ->numeric()
                            ->readOnly(),
                    ])
            ]);
    }

    public static function updateTotals(Set $set, Get $get)
    {
        $clientId = $get('client_id');
        $items = $get('items');

        $subtotal = 0;
        $taxAmount = 0;

        if (is_array($items)) {
            foreach ($items as $item) {
                $qty = (float) ($item['quantity'] ?? 0);
                $rate = (float) ($item['rate'] ?? 0);
                $amount = $qty * $rate;
                $subtotal += $amount;

                if ($clientId) {
                    $client = Client::find($clientId);
                    if ($client) {
                        $product = Product::find($item['product_id'] ?? null);
                        if ($product) {
                            $taxRate = $product->tax_rate;
                            $taxAmount += ($amount * $taxRate) / 100;
                        }
                    }
                }
            }
        }

        $set('subtotal', number_format($subtotal, 2, '.', ''));
        $set('tax_amount', number_format($taxAmount, 2, '.', ''));
        $set('grand_total', number_format($subtotal + $taxAmount, 2, '.', ''));
    }
}
