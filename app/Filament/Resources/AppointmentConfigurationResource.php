<?php

namespace App\Filament\Resources;

use App\Enums\RebookingRule;
use App\Filament\Resources\AppointmentConfigurationResource\Pages;
use App\Models\AppointmentConfiguration;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AppointmentConfigurationResource extends Resource
{
    protected static ?string $model = AppointmentConfiguration::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationLabel = 'Appointment Configuration';

    protected static ?string $navigationGroup = 'Administration';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Scheduling')
                    ->columns(2)
                    ->schema([
                        Select::make('time_interval_id')
                            ->label('Time Interval')
                            ->relationship(
                                'timeInterval',
                                'label',
                                fn (Builder $query) => $query->where('is_active', true)->orderBy('minutes')
                            )
                            ->required(),
                        TextInput::make('capacity_per_interval')
                            ->label('Appointments per Interval')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(500)
                            ->required(),
                    ]),
                Section::make('Policies')
                    ->columns(3)
                    ->schema([
                        TextInput::make('grace_period_minutes')
                            ->label('Grace Period (minutes)')
                            ->helperText('Late members are tagged no-show after this.')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(240)
                            ->required(),
                        Select::make('rebooking_rule')
                            ->label('Rebooking Rule')
                            ->options(RebookingRule::class)
                            ->required(),
                        Select::make('cancellation_cutoff_id')
                            ->label('Cancellation Allowed Until')
                            ->relationship(
                                'cancellationCutoff',
                                'label',
                                fn (Builder $query) => $query->where('is_active', true)->orderByDesc('hours')
                            )
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('branch.name')
                    ->label('Branch')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('timeInterval.label')
                    ->label('Interval'),
                TextColumn::make('capacity_per_interval')
                    ->label('Capacity / Interval'),
                TextColumn::make('grace_period_minutes')
                    ->label('Grace (min)'),
                TextColumn::make('rebooking_rule')
                    ->label('Rebooking'),
                TextColumn::make('cancellationCutoff.label')
                    ->label('Cancellation Cutoff'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAppointmentConfigurations::route('/'),
            'edit' => Pages\EditAppointmentConfiguration::route('/{record}/edit'),
        ];
    }
}
