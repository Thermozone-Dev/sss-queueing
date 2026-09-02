<?php

namespace App\Filament\Resources\CancellationCutoffResource\Pages;

use App\Filament\Resources\CancellationCutoffResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCancellationCutoff extends CreateRecord
{
    protected static string $resource = CancellationCutoffResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
