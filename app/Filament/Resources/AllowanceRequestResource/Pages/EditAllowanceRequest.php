<?php

namespace App\Filament\Resources\AllowanceRequestResource\Pages;

use App\Filament\Resources\AllowanceRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAllowanceRequest extends EditRecord
{
    protected static string $resource = AllowanceRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
