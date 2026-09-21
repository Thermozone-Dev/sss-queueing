<?php

namespace App\Filament\Resources\TimeIntervalResource\Pages;

use App\Filament\Resources\TimeIntervalResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTimeIntervals extends ListRecords
{
    protected static string $resource = TimeIntervalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
