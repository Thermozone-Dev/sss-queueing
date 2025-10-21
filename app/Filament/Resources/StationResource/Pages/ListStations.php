<?php

namespace App\Filament\Resources\StationResource\Pages;

use App\Filament\Resources\StationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;


class ListStations extends ListRecords
{
    protected static string $resource = StationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Add Station')->icon('heroicon-s-plus'),
        ];
    }

    protected function getTableQuery(): ?Builder
    {
        $query = parent::getTableQuery()->where('deleted_at','=',null);

        if (auth()->user()->hasRole('staff')) {
            return $query->whereHas('users', function ($q) {
                $q->where('users.id', auth()->id());
            });
        }
        return $query;
    }
}
