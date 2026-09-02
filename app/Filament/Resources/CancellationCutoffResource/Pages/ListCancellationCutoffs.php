<?php

namespace App\Filament\Resources\CancellationCutoffResource\Pages;

use App\Filament\Resources\CancellationCutoffResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCancellationCutoffs extends ListRecords
{
    protected static string $resource = CancellationCutoffResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
