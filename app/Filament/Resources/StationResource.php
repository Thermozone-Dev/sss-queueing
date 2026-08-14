<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StationResource\Pages;
use App\Filament\Resources\StationResource\RelationManagers;
use App\Models\Station;
use Filament\Actions\DeleteAction;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\RestoreAction;
use Guava\FilamentIconPicker\Tables\IconColumn as GuavaIconColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Guava\FilamentIconPicker\Forms\IconPicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;

class StationResource extends Resource
{
    protected static ?string $model = Station::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-library';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->columns(2)
                    ->schema([
                            Group::make()
                                ->schema([
                                    Toggle::make('status')
                                        ->default(true)
                                        ->label('Active'),

                                    TextInput::make('name')
                                        ->required()
                                        ->unique(
                                            table: 'stations',
                                            column: 'name',
                                            ignoreRecord: true,
                                            modifyRuleUsing: function (Unique $rule) {
                                                return $rule->where('branch_id', auth()->user()->branch_id);
                                            }
                                        )
                                        ->live(debounce: 500)
                                        ->afterStateUpdated(function (callable $set, $state) {
                                            $initials = collect(explode(' ', $state))
                                                ->filter()
                                                ->map(fn ($word) => strtoupper(substr($word, 0, 1)))
                                                ->take(4)
                                                ->implode('');

                                            $set('code', $initials);
                                        }),

                                    TextInput::make('code')
                                        ->maxLength(4)
                                        ->required()
                                        ->unique(ignoreRecord: true),

//                                    TextInput::make('type')
//                                        ->nullable(),
                                    Forms\Components\Textarea::make('description')
                                        ->placeholder('Optional description for the station')
                                        ->maxLength(200)
                                        ->disableGrammarly()
                                        ->rows(3),

                                ]),
                            Group::make()
                                ->schema([

                                    Toggle::make('priority_handling')
                                        ->default(true)
                                        ->label('Priority Handling'),

                                    TextInput::make('max_concurrent_clients')
                                        ->numeric()
                                        ->minValue(5)
                                        ->required(),
                                    IconPicker::make('icon')
                                        ->sets(['heroicons'])
                                        ->optionsLimit(150)
                                        ->columns([
                                            'default' => 2,
                                            'lg' => 3,
                                            '2xl' => 5,
                                        ])
                                        ->sets(['heroicons'])
                                        ->required()
                                        ->preload(),
                                    Select::make('staff')
                                        ->label('Assigned Staff')
                                        ->relationship('users', 'firstname')
                                        ->preload()
                                        ->multiple()
                                        ->getOptionLabelFromRecordUsing(fn (Model $record) => Str::headline($record->fullname)),
                                ]),

                    ]),
                ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                GuavaIconColumn::make('icon'),
                TextColumn::make('name')->searchable(),
                TextColumn::make('code')->searchable(),
                IconColumn::make('status')
                    ->boolean()
                    ->label('Active'),
                TextColumn::make('created_at')->dateTime('M d, Y g:i A')->sortable(),
                TextColumn::make('deleted_at')->dateTime('M d, Y g:i A')->sortable(),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\TransactionRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStations::route('/'),
            'create' => Pages\CreateStation::route('/create'),
            'view' => Pages\ViewStation::route('/{record}'),
            'edit' => Pages\EditStation::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

}
