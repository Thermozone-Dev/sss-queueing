<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Filament\Resources\TransactionResource;
use App\Services\TransactionService;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListTransactions extends ListRecords
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('fetch_transaction_api')
                ->label('Sync From API')
                ->color('info')
                ->action(function () {
                    try {
                        $result = app(TransactionService::class)->syncFromApi();

                        Notification::make()
                            ->success()
                            ->title('Success')
                            ->body("Transactions synced from API successfully: {$result['created']} created, {$result['updated']} updated, {$result['failed']} failed")
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->danger()
                            ->title('Error')
                            ->body($e->getMessage())
                            ->send();
                    }
                })
                ->icon('heroicon-s-arrow-path'),
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
