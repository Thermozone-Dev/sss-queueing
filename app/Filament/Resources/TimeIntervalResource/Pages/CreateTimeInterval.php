<?php

namespace App\Filament\Resources\TimeIntervalResource\Pages;

use App\Filament\Resources\TimeIntervalResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTimeInterval extends CreateRecord
{
    protected static string $resource = TimeIntervalResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
