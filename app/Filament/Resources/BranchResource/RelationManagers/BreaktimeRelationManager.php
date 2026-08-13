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
                    ->format('H:i')
                    ->displayFormat('H:i')
                    ->seconds(false)
                    ->required(),

                Forms\Components\TimePicker::make('to')
                    ->label('Break End Time')
                    ->format('H:i')
                    ->displayFormat('H:i')
                    ->seconds(false)
                    ->afterOrEqual('from')
                    ->required(),

                Forms\Components\Textarea::make('notes')
                    ->label('Break Description')
                    ->placeholder('e.g., Lunch Break, Prayer Time, Staff Meeting')
                    ->rows(3)
                    ->nullable(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('from')
                    ->label('From')
                    ->formatStateUsing(fn ($state) => $this->convertTo12HourFormat($state)),

                Tables\Columns\TextColumn::make('to')
                    ->label('To')
                    ->formatStateUsing(fn ($state) => $this->convertTo12HourFormat($state)),

                Tables\Columns\TextColumn::make('notes')
                    ->label('Description')
                    ->limit(50)
                    ->columnSpanFull()
                    ->tooltip(fn ($state) => $state),
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

    private function convertTo12HourFormat($time): string
    {
        if (!$time) return '';

        try {
            $dateTime = \DateTime::createFromFormat('H:i:s', $time);
            if ($dateTime === false) {
                return $time;
            }
            return $dateTime->format('h:i A');
        } catch (\Exception) {
            return $time;
        }
    }
}
