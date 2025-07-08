<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AllowanceHistoryResource\Pages;
use App\Models\AllowanceHistory;
use App\Models\Customer;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AllowanceHistoryResource extends Resource
{
    protected static ?string $model = AllowanceHistory::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationLabel = 'Usage History';
    protected static ?string $navigationGroup = 'Manage Request';

    public static function form(Form $form): Form
    {
        $customerId = request()->get('customer_id');
        $customer = $customerId ? \App\Models\Customer::find($customerId) : null;

        return $form->schema([
            Forms\Components\Group::make([
                // Sửa ở đây
                Forms\Components\Hidden::make('customer_id')
                    ->default($customerId)
                    ->required()
                    ->dehydrated(true),

                Forms\Components\Placeholder::make('customer_display')
                    ->label('Customer')
                    ->content($customer?->name ?? 'No customer selected')
                    ->visible(fn () => filled($customerId)),

                Forms\Components\Select::make('customer_id')
                    ->label('Customer')
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->required()
                    ->visible(fn () => blank($customerId)),
            ]),

            Forms\Components\TextInput::make('delta')
                ->label('Amount')
                ->numeric()
                ->required(),

            Forms\Components\Textarea::make('description')
                ->label('Reason / Note')
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer.name')->label('Customer')->searchable(),
                Tables\Columns\TextColumn::make('delta')->label('Amount')->money('VND'),
                Tables\Columns\TextColumn::make('balance')->label('Balance After')->money('VND'),
                Tables\Columns\BadgeColumn::make('request.status')
                    ->label('Request Status')
                    ->colors([
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ])
                    ->formatStateUsing(fn ($state) => ucfirst($state))
                    ->default('-'),
                Tables\Columns\BadgeColumn::make('type')
                    ->label('Type')
                    ->colors([
                        'success' => 'increase',
                        'danger' => 'decrease',
                    ])
                    ->formatStateUsing(fn($state) => ucfirst($state)),
                Tables\Columns\TextColumn::make('description')->label('Description')->wrap(),
                Tables\Columns\TextColumn::make('voucher.code')->label('Voucher')->default('-'),
                Tables\Columns\TextColumn::make('created_at')->label('Created At')->dateTime()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'increase' => 'Increase',
                        'decrease' => 'Decrease',
                        'refund' => 'Refund',
                    ]),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('from'),
                        Forms\Components\DatePicker::make('until'),
                    ])
                    ->query(function ($query, $data) {
                        return $query
                            ->when($data['from'], fn($q) => $q->whereDate('created_at', '>=', $data['from']))
                            ->when($data['until'], fn($q) => $q->whereDate('created_at', '<=', $data['until']));
                    }),
                Tables\Filters\SelectFilter::make('request.status')
                    ->label('Request Status')
                    ->options([
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
            ])
            ->actions([])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAllowanceHistories::route('/'),
            'create-manual' => Pages\CreateManualUsageRecord::route('/create-manual'),
        ];
    }

    public static function canCreate(): bool
    {
        return true;
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false;
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false;
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasPermission('view_customer_history');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->hasPermission('view_customer_history');
    }

}
