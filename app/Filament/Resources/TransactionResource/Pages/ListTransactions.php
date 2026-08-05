<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Filament\Resources\TransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListTransactions extends ListRecords
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Add Transaction')->icon('heroicon-s-plus'),
        ];
    }

    protected function getTableQuery(): ?Builder
    {
        $query = parent::getTableQuery();

        // if (auth()->user()->hasRole('staff')) {
        //     return $query->whereHas('users', function ($q) {
        //         $q->where('users.id', auth()->id());
        //     });
        // }

        return $query;
    }
}
