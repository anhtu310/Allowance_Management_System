<?php

namespace App\Filament\Resources\AllowanceHistoryResource\Pages;

use App\Filament\Resources\AllowanceHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAllowanceHistories extends ListRecords
{
    protected static string $resource = AllowanceHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
