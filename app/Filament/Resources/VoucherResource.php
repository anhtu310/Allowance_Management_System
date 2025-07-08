<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VoucherResource\Pages;
use App\Models\Voucher;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Model;

class VoucherResource extends Resource
{
    protected static ?string $model = Voucher::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $navigationGroup = 'Manage Request';
    protected static ?string $navigationLabel = 'Vouchers';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('code')->label('Voucher Code')->disabled(),
            Forms\Components\Select::make('status')
                ->options([
                    'unused' => 'Unused',
                    'used' => 'Used',
                    'expired' => 'Expired',
                ])
                ->label('Status')
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')->label('Code')->searchable()->copyable(),
                Tables\Columns\TextColumn::make('status')->label('Status')->badge()
                    ->colors([
                        'primary' => 'unused',
                        'success' => 'used',
                        'danger' => 'expired',
                    ]),
                Tables\Columns\TextColumn::make('history.customer.name')->label('Customer')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('created_at')->label('Created At')->dateTime()->sortable(),
            ])
            ->filters([
                // Lọc theo trạng thái voucher
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'unused' => 'Unused',
                        'used' => 'Used',
                        'expired' => 'Expired',
                    ]),

                // Lọc theo customer
                SelectFilter::make('customer_id')
                    ->label('Customer')
                    ->relationship('history.customer', 'name'),

                // Lọc theo khoảng ngày tạo
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('from')->label('From'),
                        Forms\Components\DatePicker::make('until')->label('To'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['from'], fn ($q) => $q->whereDate('created_at', '>=', $data['from']))
                            ->when($data['until'], fn ($q) => $q->whereDate('created_at', '<=', $data['until']));
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVouchers::route('/'),
            'create' => Pages\CreateVoucher::route('/create'),
            'edit' => Pages\EditVoucher::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }
}
