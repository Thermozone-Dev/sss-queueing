<?php

namespace App\Filament\Resources\BranchResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class BusinessDayRelationManager extends RelationManager
{
    protected static string $relationship = 'businessDay';

    protected static ?string $recordTitleAttribute = 'id';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Operating Days')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Checkbox::make('monday')
                            ->label('Monday')
                            ->default(true),

                        Forms\Components\Checkbox::make('tuesday')
                            ->label('Tuesday')
                            ->default(true),

                        Forms\Components\Checkbox::make('wednesday')
                            ->label('Wednesday')
                            ->default(true),

                        Forms\Components\Checkbox::make('thursday')
                            ->label('Thursday')
                            ->default(true),

                        Forms\Components\Checkbox::make('friday')
                            ->label('Friday')
                            ->default(true),

                        Forms\Components\Checkbox::make('saturday')
                            ->label('Saturday')
                            ->default(false),

                        Forms\Components\Checkbox::make('sunday')
                            ->label('Sunday')
                            ->default(false),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {

        /** Pending Task
         * The head office can assign a business days to this branch
         * It must be a header action with a form of checkbox of each column to save to set to active or inactive each day
         * the branch_head role and  below hierarchy cant assign a transaction for their branch
         * the table must only have one conditional action if that days is active the action will set to set this day to Inactive? and vise versa
         */


        return $table
            ->columns([
                Tables\Columns\ViewColumn::make('days')
                    ->label('Operating Days')
                    ->view('filament.tables.columns.business-days'),
            ])
            ->headerActions([
                Tables\Actions\EditAction::make()
                    ->visible(fn () => auth()->user()->hasRole('head_office')),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn () => auth()->user()->hasRole('head_office')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
