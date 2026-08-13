<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Filament\Resources\TransactionResource;
use App\Models\APIResponse;
use App\Models\Transaction;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

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
                        $result = APIResponse::fetchLatestAPIRecord(2);
                        if ($result) {
                            $result->map(function ($item){
                                    $transaction = Transaction::updateOrCreate(
                                        [
                                            'code' => $item['code'],
                                            'transaction_id_api' => $item['transaction_id_api']
                                        ],
                                        $item
                                    );
                                    Log::info("[Transaction: {$item['code']}] " . ($transaction->wasRecentlyCreated ? "Created" : "Updated") . " from API", [
                                        'code' => $item['code'],
                                        'name' => $item['name'],
                                        'category' => $item['category'] ?? null,
                                        'action' => $transaction->wasRecentlyCreated ? 'CREATE' : 'UPDATE',
                                    ]);
                            });

                           Notification::make()
                                ->success()
                                ->title('Success')
                                ->body('Transactions synced from API successfully')
                                ->send();
                        }
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
