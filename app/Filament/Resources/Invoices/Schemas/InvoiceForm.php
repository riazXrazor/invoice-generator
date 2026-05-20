<?php

namespace App\Filament\Resources\Invoices\Schemas;

use App\Models\Client;
use App\Models\CompanyDetail;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\State;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class InvoiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Grid::make()
                ->schema([
                    Section::make('Invoice Details')
                        ->schema([
                            Grid::make(2)->schema([
                                Select::make('client_id')
                                    ->relationship('client', 'name')
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(fn (Set $set, Get $get) => self::updateTotals($set, $get)),
                                TextInput::make('invoice_no')
                                    ->default(fn () => CompanyDetail::first()->invoice_prefix.'/'.str_pad(Invoice::count() + 1, 3, '0', STR_PAD_LEFT).'/'.((date('y')).'-'.(date('y') + 1)))
                                    ->required()
                                    ->unique(ignoreRecord: true),
                                DatePicker::make('invoice_date')
                                    ->default(now())
                                    ->required(),
                                TextInput::make('challan_no'),
                                TextInput::make('dispatched_through')->columnSpanFull(),
                                Checkbox::make('cc_attach')
                                    ->label('C.C. ATTACH')
                                    ->live()->columnSpanFull(),
                                TextInput::make('cc_phone')
                                    ->label('Phone Number')
                                    ->visible(fn (Get $get): bool => (bool) $get('cc_attach'))
                            ]),
                            Section::make('Shipping Details')
                                ->schema([
                                    Toggle::make('has_different_shipping_address')
                                        ->label('Shipping address is different from buyer')
                                        ->live(),
                                    TextInput::make('shipping_name')
                                        ->label('Shipping Name')
                                        ->visible(fn (Get $get): bool => $get('has_different_shipping_address')),
                                    Textarea::make('shipping_address')
                                        ->label('Shipping Address')
                                        ->visible(fn (Get $get): bool => $get('has_different_shipping_address')),
                                    TextInput::make('shipping_gstin')
                                        ->label('Shipping GSTIN/UTN')
                                        ->visible(fn (Get $get): bool => $get('has_different_shipping_address')),
                                    Select::make('shipping_state_code')
                                        ->label('Shipping State')
                                        ->options(State::all()->pluck('name_with_code', 'code'))
                                        ->searchable()
                                        ->visible(fn (Get $get): bool => $get('has_different_shipping_address')),
                                ])->columnSpanFull(),
                        ])->columnSpanFull(),

                    Section::make('Totals')
                        ->schema([
                            Hidden::make('tax_rate_igst')->dehydrated(false),
                            Hidden::make('tax_rate_cgst')->dehydrated(false),
                            Hidden::make('tax_rate_sgst')->dehydrated(false),
                            Hidden::make('is_intra_state')->dehydrated(false),

                            TextInput::make('subtotal')
                                ->readOnly()->columnSpanFull(),
                            TextInput::make('igst_amount')
                                ->label(fn (Get $get) => $get('tax_rate_igst') ? 'IGST @ '.$get('tax_rate_igst').'%' : 'IGST')
                                ->readOnly()
                                ->visible(fn (Get $get) => ! $get('is_intra_state'))
                                ->dehydrated(false)->columnSpanFull(),
                            TextInput::make('cgst_amount')
                                ->label(fn (Get $get) => $get('tax_rate_cgst') ? 'CGST @ '.$get('tax_rate_cgst').'%' : 'CGST')
                                ->readOnly()
                                ->visible(fn (Get $get) => (bool) $get('is_intra_state'))
                                ->dehydrated(false)->columnSpanFull(),
                            TextInput::make('sgst_amount')
                                ->label(fn (Get $get) => $get('tax_rate_sgst') ? 'SGST @ '.$get('tax_rate_sgst').'%' : 'SGST')
                                ->readOnly()
                                ->visible(fn (Get $get) => (bool) $get('is_intra_state'))
                                ->dehydrated(false)->columnSpanFull(),
                            TextInput::make('tax_amount')
                                ->label('Total Tax Amount')
                                ->readOnly()->columnSpanFull(),
                            TextInput::make('round_off')
                                ->readOnly()
                                ->dehydrated(false)->columnSpanFull(),
                            TextInput::make('grand_total')
                                ->readOnly()
                                ->columnSpanFull(),
                        ])->columnSpanFull(),
                ]),
            Section::make('Items')
                ->schema([
                    Repeater::make('items')
                        ->relationship()
                        ->columns(5)
                        ->live()
                        ->afterStateHydrated(fn (Set $set, Get $get) => self::updateTotals($set, $get))
                        ->afterStateUpdated(fn (Set $set, Get $get) => self::updateTotals($set, $get))
                        ->schema([
                            Select::make('product_id')
                                ->relationship('product', 'description')
                                ->required()
                                ->live()
                                ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                    $product = Product::find($state);
                                    if ($product) {
                                        $set('rate', $product->default_rate);
                                        $set('tax_rate', $product->tax_rate);
                                    }
                                    $qty = (float) $get('quantity') ?: 1;
                                    $rate = (float) $get('rate') ?: 0;
                                    $taxRate = (float) $get('tax_rate') ?: 0;
                                    $amount = $qty * $rate;
                                    $set('amount', $amount);
                                    $set('tax_amount', ($amount * $taxRate) / 100);
                                }),
                            TextInput::make('quantity')
                                ->numeric()
                                ->default(1)
                                ->required()
                                ->live()
                                ->afterStateUpdated(function (Set $set, Get $get) {
                                    $qty = (float) $get('quantity') ?: 1;
                                    $rate = (float) $get('rate') ?: 0;
                                    $taxRate = (float) $get('tax_rate') ?: 0;
                                    $amount = $qty * $rate;
                                    $set('amount', $amount);
                                    $set('tax_amount', ($amount * $taxRate) / 100);
                                }),
                            TextInput::make('rate')
                                ->numeric()
                                ->required()
                                ->live()
                                ->afterStateUpdated(function (Set $set, Get $get) {
                                    $qty = (float) $get('quantity') ?: 1;
                                    $rate = (float) $get('rate') ?: 0;
                                    $taxRate = (float) $get('tax_rate') ?: 0;
                                    $amount = $qty * $rate;
                                    $set('amount', $amount);
                                    $set('tax_amount', ($amount * $taxRate) / 100);
                                }),
                            TextInput::make('tax_rate')
                                ->numeric()
                                ->label('Tax %')
                                ->live()
                                ->afterStateUpdated(function (Set $set, Get $get) {
                                    $qty = (float) $get('quantity') ?: 1;
                                    $rate = (float) $get('rate') ?: 0;
                                    $taxRate = (float) $get('tax_rate') ?: 0;
                                    $amount = $qty * $rate;
                                    $set('tax_amount', ($amount * $taxRate) / 100);
                                }),
                            TextInput::make('amount')
                                ->numeric()
                                ->required()
                                ->readOnly(),
                            Hidden::make('tax_amount')
                                ->dehydrated(true),
                        ]),
                ]),
        ]);

    }

    public static function updateTotals(Set $set, Get $get)
    {
        $clientId = $get('client_id');
        $items = $get('items');

        $subtotal = 0;
        $taxAmount = 0;
        $taxRateText = 0;

        if (is_array($items)) {
            $firstItem = reset($items);
            if (isset($firstItem['tax_rate']) && is_numeric($firstItem['tax_rate'])) {
                $taxRateText = (float) $firstItem['tax_rate'];
            } elseif (! empty($firstItem['product_id'])) {
                $product = Product::find($firstItem['product_id']);
                if ($product) {
                    $taxRateText = $product->tax_rate;
                }
            }

            foreach ($items as $item) {
                $qty = (float) ($item['quantity'] ?? 0);
                $rate = (float) ($item['rate'] ?? 0);
                $amount = $qty * $rate;
                $subtotal += $amount;

                if ($clientId) {
                    $client = Client::find($clientId);
                    if ($client) {
                        $taxRate = 0;
                        if (isset($item['tax_rate']) && is_numeric($item['tax_rate'])) {
                            $taxRate = (float) $item['tax_rate'];
                        } elseif (! empty($item['product_id'])) {
                            $product = Product::find($item['product_id']);
                            if ($product) {
                                $taxRate = $product->tax_rate;
                            }
                        }
                        $taxAmount += ($amount * $taxRate) / 100;
                    }
                }
            }
        }

        $igst_amount = 0;
        $cgst_amount = 0;
        $sgst_amount = 0;

        $client = Client::find($clientId);
        $company = CompanyDetail::first();

        if ($client && $company) {
            $igst = $taxRateText;
            $cgst = 0;
            $sgst = 0;

            $igst_amount = $taxAmount;
            $isIntraState = false;

            if ($client->state_code === $company->state_code) {
                $isIntraState = true;
                $cgst = $igst / 2;
                $sgst = $igst / 2;

                $cgst_amount = $taxAmount / 2;
                $sgst_amount = $taxAmount / 2;
                $igst_amount = 0;
            }

            $set('tax_rate_cgst', $cgst);
            $set('tax_rate_sgst', $sgst);
            $set('tax_rate_igst', $igst);
            $set('is_intra_state', $isIntraState);
        }

        $grandTotalExact = $subtotal + $taxAmount;
        $grandTotalRounded = round($grandTotalExact);
        $roundOff = $grandTotalRounded - $grandTotalExact;

        $set('subtotal', number_format($subtotal, 2, '.', ''));
        $set('tax_amount', number_format($taxAmount, 2, '.', ''));
        $set('igst_amount', number_format($igst_amount, 2, '.', ''));
        $set('cgst_amount', number_format($cgst_amount, 2, '.', ''));
        $set('sgst_amount', number_format($sgst_amount, 2, '.', ''));
        $set('round_off', number_format($roundOff, 2, '.', ''));
        $set('grand_total', number_format($grandTotalRounded, 2, '.', ''));
    }
}
