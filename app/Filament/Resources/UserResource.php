<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\Role;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $navigationLabel = 'Users';
    protected static ?string $navigationGroup = 'System Management';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(100),

            Forms\Components\TextInput::make('email')
                ->email()
                ->required()
                ->maxLength(100),

            Forms\Components\Hidden::make('password')
                ->default(fn () => \Hash::make(\Str::random(10))),

            // Gán role HR mặc định khi tạo user
            Forms\Components\Hidden::make('role_id')
                ->default(fn() => Role::where('name', 'HR')->value('id')),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query) =>
            $query->whereHas('role', fn($q) => $q->where('name', 'HR'))
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('role.name')->label('Role'),
                Tables\Columns\TextColumn::make('created_at')->label('Ngày tạo')->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')->label('Cập nhật')->dateTime(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasPermission('manage_users');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->hasPermission('manage_users');
    }
}
