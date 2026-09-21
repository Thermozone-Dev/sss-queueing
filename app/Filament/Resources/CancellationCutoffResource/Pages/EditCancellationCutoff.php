<?php

namespace App\Filament\Resources\CancellationCutoffResource\Pages;

use App\Filament\Resources\CancellationCutoffResource;
use Filament\Resources\Pages\EditRecord;

class EditCancellationCutoff extends EditRecord
{
    protected static string $resource = CancellationCutoffResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
