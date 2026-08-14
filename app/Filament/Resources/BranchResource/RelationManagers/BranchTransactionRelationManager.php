<?php

namespace App\Filament\Resources\BranchResource\RelationManagers;

use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;

class BranchTransactionRelationManager extends RelationManager
{
    protected static string $relationship = 'branchTransactions';

    protected static ?string $recordTitleAttribute = 'id';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('transaction_id')
                    ->label('Transaction')
                    ->options(Transaction::pluck('name', 'id'))
                    ->required()
                    ->searchable()
                    ->disabled(fn (string $operation) => $operation === 'edit'),

                Forms\Components\Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('transaction.code')
                    ->label('Code')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('transaction.name')
                    ->label('Transaction')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('transaction.description')
                    ->label('Description')
                    ->limit(50)
                    ->wrap(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Action::make('assignTransactions')
                    ->label('Assign Transactions')
                    ->icon('heroicon-m-plus')
                    ->visible(fn () => auth()->user()->hasRole('head_office') || auth()->user()->hasRole('super_admin'))
                    ->fillForm(function (): array  {
                        $branch = $this->getOwnerRecord();
                        return [
                            'transaction_ids' => $branch->branchTransactions->pluck('transaction_id')->toArray(),
                        ];
                    })
                    ->form([
                        Select::make('transaction_ids')
                            ->label('Select Transactions')
                            ->options(fn () => Transaction::pluck('name', 'id')->toArray())
                            ->searchable()
                            ->multiple()
                            ->required(),
                    ])
                    ->action(function (array $data): void {
                        $branch = $this->getOwnerRecord();
                        foreach ($data['transaction_ids'] as $transactionId) {
                            \App\Models\BranchTransaction::firstOrCreate([
                                'branch_id' => $branch->id,
                                'transaction_id' => $transactionId,
                            ], [
                                'is_active' => true,
                            ]);
                        }
                    }),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make()->label('Remove'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}
