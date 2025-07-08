<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AllowanceRequestResource\Pages;
use App\Mail\AllowanceRequestResultMail;
use App\Models\AllowanceHistory;
use App\Models\AllowanceRequest;
use App\Models\Notification;
use App\Models\Voucher;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class AllowanceRequestResource extends Resource
{
    protected static ?string $model = AllowanceRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationLabel = 'Pending Request';
    protected static ?string $navigationGroup = 'Manage Request';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('customer_id')->label('Customer')->disabled(),
            Forms\Components\TextInput::make('amount_requested')->label('Amount')->disabled(),
            Forms\Components\Textarea::make('reason')->label('Reason')->disabled(),
            Forms\Components\Select::make('status')->label('Status')
                ->options(['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected'])
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(static::getEloquentQuery())
            ->columns([
                Tables\Columns\TextColumn::make('customer.name')->label('Customer')->searchable(),
                Tables\Columns\TextColumn::make('amount_requested')->label('Amount')->searchable(),
                Tables\Columns\TextColumn::make('reason')->label('Reason')->searchable(),
                Tables\Columns\TextColumn::make('created_at')->label('Requested At')->dateTime()->sortable(),
            ])
            ->filters([
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
                Tables\Actions\Action::make('View Details')
                    ->label('View Details')
                    ->icon('heroicon-o-eye')
                    ->modalHeading('Review Request')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close')
                    ->modalContent(fn($record) => view('filament.resources.allowance-request-resource.partials.request-details', compact('record')))
                    ->modalActions([
                        // ✅ APPROVE
                        Tables\Actions\Action::make('Approve')
                            ->action(function ($record, Tables\Actions\Action $action) {
                                DB::transaction(function () use ($record) {
                                    $customer = $record->customer;
                                    $amount = $record->amount_requested;

                                    // ✅ Tạo voucher
                                    $voucher = Voucher::create([
                                        'code' => strtoupper('VC-' . \Str::random(10)),
                                        'status' => 'unused',
                                    ]);

                                    // ✅ Ghi lại lịch sử chi tiêu
                                    $history = AllowanceHistory::create([
                                        'balance' => $customer->total_allowance,
                                        'delta' => $amount,
                                        'type' => 'decrease',
                                        'description' => 'Approved pending request',
                                        'request_id' => $record->id,
                                        'customer_id' => $customer->id,
                                        'vouchers_id' => $voucher->id,
                                    ]);

                                    // ✅ Cập nhật trạng thái
                                    $record->update([
                                        'status' => 'approved',
                                        'handled_by' => auth()->id(),
                                        'handled_at' => now(),
                                    ]);

                                    Mail::to($customer->email)->send(new AllowanceRequestResultMail($record));

                                    $notification = Notification::create([
                                        'title' => 'Your allowance request was approved.',
                                        'body' => 'Amount approved: ' . number_format($amount),
                                        'content' => view('emails.allowance-request-result', [
                                            'request' => $record->fresh(),
                                        ])->render(),
                                        'customer_id' => $customer->id,
                                        'type' => 'approved',
                                        'related_model_type' => AllowanceHistory::class,
                                        'related_model_id' => $history->id,
                                    ]);
                                    $history->notifications_id = $notification->id;
                                    $history->save();
                                });

                                \Filament\Notifications\Notification::make()
                                    ->title('Request approved and voucher generated.')
                                    ->success()
                                    ->send();

                                $action->success();
                                $action->cancelParentActions();
                            }),

                        Tables\Actions\Action::make('Reject')
                            ->color('danger')
                            ->label('Reject')
                            ->requiresConfirmation()
                            ->action(function ($record, Tables\Actions\Action $action) {
                                DB::transaction(function () use ($record) {
                                    $customer = $record->customer;
                                    $amount = $record->amount_requested;

                                    // ✅ Hoàn lại tiền
                                    $customer->increment('total_allowance', $amount);

                                    $history = AllowanceHistory::create([
                                        'balance' => $customer->fresh()->total_allowance,
                                        'delta' => $amount,
                                        'type' => 'refund',
                                        'description' => 'Request was rejected',
                                        'request_id' => $record->id,
                                        'customer_id' => $customer->id,
                                        'vouchers_id' => null,
                                    ]);

                                    $record->update([
                                        'status' => 'rejected',
                                        'handled_by' => auth()->id(),
                                        'handled_at' => now(),
                                    ]);

                                    Mail::to($customer->email)->send(new AllowanceRequestResultMail($record));

                                    $notification = Notification::create([
                                        'title' => 'Your allowance request was rejected.',
                                        'body' => 'Your request has been reviewed and rejected by HR.',
                                        'content' => view('emails.allowance-request-result', [
                                            'request' => $record->fresh(),
                                        ])->render(),
                                        'customer_id' => $customer->id,
                                        'type' => 'rejected',
                                        'related_model_type' => AllowanceHistory::class,
                                        'related_model_id' => $history->id,
                                    ]);

                                    $history->notifications_id = $notification->id;
                                    $history->save();
                                });

                                \Filament\Notifications\Notification::make()
                                    ->title('Request rejected and amount refunded.')
                                    ->warning()
                                    ->send();

                                $action->success();
                                $action->cancelParentActions();
                            }),
                    ])
            ])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAllowanceRequests::route('/'),
        ];
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('status', 'pending');
    }

    public static function canCreate(): bool
    {
        return false;
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
        return auth()->user()?->hasPermission('manage_requests');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->hasPermission('manage_requests');
    }
}
