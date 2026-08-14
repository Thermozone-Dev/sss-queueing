<?php

namespace App\Filament\Resources\BranchResource\Pages;

use App\Filament\Resources\BranchResource;
use App\Services\BranchSyncService;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListBranches extends ListRecords
{
    protected static string $resource = BranchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('sync_branches')
                ->label('Sync Branches')
                ->icon('heroicon-o-arrow-path')
                ->action(function () {
                    $result = app(BranchSyncService::class)->syncAll();

                    Notification::make()
                        ->title('Branches Synced')
                        ->body("Created: {$result['created']}, Updated: {$result['updated']}, Failed: {$result['failed']}")
                        ->success()
                        ->send();
                }),
            Actions\CreateAction::make(),
        ];
    }
}
