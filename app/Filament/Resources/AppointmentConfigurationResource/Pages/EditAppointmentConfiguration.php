<?php

namespace App\Filament\Resources\AppointmentConfigurationResource\Pages;

use App\Filament\Resources\AppointmentConfigurationResource;
use Filament\Resources\Pages\EditRecord;

class EditAppointmentConfiguration extends EditRecord
{
    protected static string $resource = AppointmentConfigurationResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
