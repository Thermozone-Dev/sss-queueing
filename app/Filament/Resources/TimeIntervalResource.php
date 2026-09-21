<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TimeIntervalResource\Pages;
use App\Models\TimeInterval;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TimeIntervalResource extends Resource
{
    protected static ?string $model = TimeInterval::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationGroup = 'Administration';

    protected static ?string $navigationLabel = 'Time Intervals';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('minutes')
                    ->label('Minutes')
                    ->numeric()
                    ->minValue(5)
                    ->maxValue(480)
                    ->unique(ignoreRecord: true)
                    ->required(),
                TextInput::make('label')
                    ->label('Label')
                    ->maxLength(255)
                    ->required(),
                Toggle::make('is_active')
                    ->label('Active')
                    ->helperText('Inactive options stay on existing configurations but cannot be newly selected.')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('minutes')
            ->columns([
                TextColumn::make('minutes')->label('Minutes')->sortable(),
                TextColumn::make('label')->label('Label'),
                IconColumn::make('is_active')->label('Active')->boolean(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTimeIntervals::route('/'),
            'create' => Pages\CreateTimeInterval::route('/create'),
            'edit' => Pages\EditTimeInterval::route('/{record}/edit'),
        ];
    }
}
