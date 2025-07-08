<?php

namespace App\Filament\Resources\AllowanceRequestResource\Pages;

use App\Filament\Resources\AllowanceRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAllowanceRequests extends ListRecords
{
    protected static string $resource = AllowanceRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
