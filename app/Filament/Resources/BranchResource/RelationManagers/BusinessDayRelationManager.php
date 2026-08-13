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
        return $table
            ->columns([
                Tables\Columns\IconColumn::make('monday')
                    ->label('Monday')
                    ->boolean(),

                Tables\Columns\IconColumn::make('tuesday')
                    ->label('Tuesday')
                    ->boolean(),

                Tables\Columns\IconColumn::make('wednesday')
                    ->label('Wednesday')
                    ->boolean(),

                Tables\Columns\IconColumn::make('thursday')
                    ->label('Thursday')
                    ->boolean(),

                Tables\Columns\IconColumn::make('friday')
                    ->label('Friday')
                    ->boolean(),

                Tables\Columns\IconColumn::make('saturday')
                    ->label('Saturday')
                    ->boolean(),

                Tables\Columns\IconColumn::make('sunday')
                    ->label('Sunday')
                    ->boolean(),
            ])
            ->headerActions([
                Tables\Actions\Action::make('configureBusinessDays')
                    ->label('Configure Business Days')
                    ->icon('heroicon-m-cog-6-tooth')
                    ->form([
                        Forms\Components\Section::make('Select Operating Days')
                            ->columns(2)
                            ->schema([
                                Forms\Components\Checkbox::make('monday')
                                    ->label('Monday')->default(true),
                                Forms\Components\Checkbox::make('tuesday')
                                    ->label('Tuesday')->default(true),
                                Forms\Components\Checkbox::make('wednesday')
                                    ->label('Wednesday')->default(true),
                                Forms\Components\Checkbox::make('thursday')
                                    ->label('Thursday')->default(true),
                                Forms\Components\Checkbox::make('friday')
                                    ->label('Friday')->default(true),
                                Forms\Components\Checkbox::make('saturday')
                                    ->label('Saturday')->default(false),
                                Forms\Components\Checkbox::make('sunday')
                                    ->label('Sunday')->default(false),
                            ]),
                    ])
                    ->action(function (array $data): void {
                        $record = $this->getOwnerRecord();
                        $businessDay = $record->businessDay ?? $record->businessDay()->create();

                        $businessDay->update($data);

                        \Filament\Notifications\Notification::make()
                            ->success()
                            ->title('Business Days Updated')
                            ->body('Operating days have been configured successfully')
                            ->send();
                    }),
            ])
            ->actions([

            ])
            ->bulkActions([
            ]);
    }
}
