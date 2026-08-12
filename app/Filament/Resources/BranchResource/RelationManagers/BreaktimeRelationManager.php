<?php

namespace App\Filament\Resources\BranchResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class BreaktimeRelationManager extends RelationManager
{
    protected static string $relationship = 'breakTimes';

    protected static ?string $recordTitleAttribute = 'from';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TimePicker::make('from')
                    ->label('Break Start Time')
                    ->required(),

                Forms\Components\TimePicker::make('to')
                    ->label('Break End Time')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {

        /** Pending Task
         * On here kindly check why the page is prompting a page expired even I am logged as super_admin
         * 
        */


        return $table
            ->columns([
                Tables\Columns\TextColumn::make('from')
                    ->label('From')
                    ->time('H:i'),

                Tables\Columns\TextColumn::make('to')
                    ->label('To')
                    ->time('H:i'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
