<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CancellationCutoffResource\Pages;
use App\Models\CancellationCutoff;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CancellationCutoffResource extends Resource
{
    protected static ?string $model = CancellationCutoff::class;

    protected static ?string $navigationIcon = 'heroicon-o-x-circle';

    protected static ?string $navigationGroup = 'Appointment Options';

    protected static ?string $navigationLabel = 'Cancellation Cutoffs';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('hours')
                    ->label('Hours Before Appointment')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(168)
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
            ->defaultSort('hours', 'desc')
            ->columns([
                TextColumn::make('hours')->label('Hours')->sortable(),
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
            'index' => Pages\ListCancellationCutoffs::route('/'),
            'create' => Pages\CreateCancellationCutoff::route('/create'),
            'edit' => Pages\EditCancellationCutoff::route('/{record}/edit'),
        ];
    }
}
