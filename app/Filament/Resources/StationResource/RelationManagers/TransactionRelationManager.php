<?php

namespace App\Filament\Resources\StationResource\RelationManagers;

use App\Models\Station;
use App\Models\TransactionStepStation;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\DetachAction;
use Filament\Tables\Actions\DetachBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Override;

class TransactionRelationManager extends RelationManager
{
    protected static string $relationship = 'stationTransactions';


    #[Override]
    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        $user = Auth::user();

        if (!$user) {
            return false;
        }

        // super_admin and head_office can manage all stations
        if ($user->hasRole(['super_admin', 'head_office'])) {
            return true;
        }

        // branch_head and branch_staff can only manage stations in their branch
        if ($user->hasRole(['branch_head', 'branch_staff'])) {
            return $user->branch_id === $ownerRecord->branch_id;
        }

        return false;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('transaction_id'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('transaction.name')
            ->columns([
                TextColumn::make('transaction.code')
                    ->label('Code')
                    ->sortable(),
                TextColumn::make('transaction.name')
                    ->label('Transaction')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('transaction.description')
                    ->label('Description')
                    ->limit(50)
                    ->wrap(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\Action::make('assignTransactions')
                    ->label('Allocate Transactions')
                    ->icon('heroicon-s-plus')
                    ->form([
                        Select::make('transaction_ids')
                            ->label('Select Transactions')
                            ->options(function () {
                                $station = $this->getOwnerRecord();
                                $branchAllocatedTransactionIds = $station->branch
                                    ->branchTransactions()
                                    ->where('is_active', true)
                                    ->pluck('transaction_id')
                                    ->toArray();

                                return \App\Models\Transaction::whereIn('id', $branchAllocatedTransactionIds)
                                    ->pluck('name', 'id')
                                    ->toArray();
                            })
                            ->searchable()
                            ->multiple()
                            ->required(),
                    ])
                    ->action(function (array $data): void {
                        $station = $this->getOwnerRecord();
                        $station->transactions()->syncWithoutDetaching($data['transaction_ids']);

                        // Create TransactionStepStation records for each step in allocated transactions
                        foreach ($data['transaction_ids'] as $transactionId) {
                            $transaction = \App\Models\Transaction::find($transactionId);
                            if ($transaction && $transaction->transaction_steps) {
                                foreach ($transaction->transaction_steps as $step) {
                                    \App\Models\TransactionStepStation::firstOrCreate([
                                        'branch_id' => $station->branch_id,
                                        'transaction_id' => $transactionId,
                                        'transaction_step_id' => $step->id,
                                        'station_id' => $station->id,
                                    ]);
                                }
                            }
                        }

                        Notification::make()
                            ->success()
                            ->title('Transactions Allocated')
                            ->body('Selected transactions have been successfully allocated to this station.')
                            ->send();
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('assignSteps')
                    ->label('Manage Transaction Steps')
                    ->icon('heroicon-m-cog-6-tooth')
                    ->visible(fn ($record) => $record->transaction->transaction_steps->count() > 0)
                    ->form(function ($record) {
                        $station = $this->getOwnerRecord();
                        $transaction = $record->transaction;
                        $steps = $transaction->transaction_steps;

                        $formSchema = [];

                        foreach ($steps as $step) {
                            $formSchema[] = Select::make("station_for_step_{$step->id}")
                                ->label($step->title)
                                ->options(function () use ($station) {
                                    return Station::where('branch_id', $station->branch_id)
                                        ->pluck('name', 'id')
                                        ->toArray();
                                })
                                ->default(function () use ($step, $record) {
                                    $stepStation = TransactionStepStation::where([
                                        'transaction_id' => $record->transaction_id,
                                        'transaction_step_id' => $step->id,
                                    ])->first();

                                    return $stepStation?->station_id;
                                })
                                ->inlineLabel()
                                ->required();
                        }
                        return $formSchema;
                    })
                    ->action(function (array $data, $record): void {
                        $station = $this->getOwnerRecord();
                        $transaction = $record->transaction;
                        $steps = $transaction->transaction_steps;
                        if($steps->isNotEmpty()){

                        }
                        foreach ($steps as $step) {
                            $assignedStationId = $data["station_for_step_{$step->id}"];

                            TransactionStepStation::updateOrCreate(
                                [
                                    'branch_id' => $station->branch_id,
                                    'transaction_id' => $record->transaction_id,
                                    'transaction_step_id' => $step->id,
                                ],
                                [
                                    'station_id' => $assignedStationId,
                                ]
                            );
                        }

                        Notification::make()
                            ->success()
                            ->title('Steps Assigned')
                            ->body('Transaction steps have been successfully assigned to stations.')
                            ->send();
                    }),

                DetachAction::make()->authorize(true),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    DetachBulkAction::make(),
                ]),
            ]);
    }
}
