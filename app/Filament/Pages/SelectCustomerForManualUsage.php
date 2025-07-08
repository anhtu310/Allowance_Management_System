<?php

namespace App\Filament\Pages;

use App\Models\Customer;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;

class SelectCustomerForManualUsage extends Page implements HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static ?string $navigationLabel = 'Manual Usage';
    protected static ?string $navigationGroup = 'Manage Request';
    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';
    protected static ?int $navigationSort = 50;

    protected static string $view = 'filament.pages.select-customer-for-manual-usage';

    public function table(Table $table): Table
    {
        return $table
            ->query(Customer::query())
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Customer Name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Phone')
                    ->searchable(),
            ])
            ->actions([
                Action::make('Select')
                    ->url(fn (Customer $record) => route('filament.admin.resources.allowance-histories.create-manual', ['customer_id' => $record->id]))
                    ->label('Record Usage'),
            ]);
    }
}
