<?php

namespace App\Filament\Resources\StationResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionRelationManager extends RelationManager
{
    protected static string $relationship = 'transactions';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->columns(2)
                    ->schema([
                        Group::make()
                            ->schema([
                                TextInput::make('name')
                                    ->required(),

                                TextInput::make('code')
                                    ->required()
                                    ->maxLength(4)
                                    ->unique(ignoreRecord: true),

                                Textarea::make('description')
                                    ->rows(3)
                                    ->nullable(),
                            ]),
                        Group::make()
                            ->schema([
                                Forms\Components\RichEditor::make('required_documents')
                                    ->toolbarButtons([
                                        'bulletList',
                                    ])
                                    ->default('<ul><li>Requirements</li></ul>')
                            ]),
                    ]),
                Section::make('Steps')
                    ->schema([
                        Repeater::make('transaction_steps')
                        ->label('Transaction Steps')
                        ->relationship('transaction_steps')
                        ->schema([
                            TextInput::make('title')->required(),
                            Grid::make(3)
                                ->schema([
                                    Select::make('linked_station_id')
                                        ->label('Linked Station')
                                        ->hint('Select the station associated with this step.')
                                        ->hintColor('primary')
                                        ->columnSpan(2)
                                        ->required(fn ($get) => $get('is_required'))
                                        ->relationship(name: 'linked_station', titleAttribute: 'name'),
                                    Toggle::make('is_required')
                                        ->label('Station Lined up?')
                                        ->inline(false)
                                        ->hintIcon('heroicon-o-information-circle', tooltip: 'Use this if the customer should be lined up on assign station.')
                                        ->default(true),
                                ]),
                            Textarea::make('description')->rows(3),
                        ])
                        ->orderable('sort_order')
                        ->defaultItems(0)
                        ->addActionLabel('Add Step')
                        ->columns(1)
                        ->rule(function () {
                            return function ($attribute, $value, $fail) {
                                $hasRequired = collect($value)->contains(function ($item) {
                                    return isset($item['is_required']) && $item['is_required'];
                                });

                                if (! $hasRequired) {
                                    $fail('At least one transaction step should be lined up by customer.');
                                }
                            };
                        }),
                    ])
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('transaction')
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('code')->sortable(),
                TextColumn::make('description')->sortable(),
                TextColumn::make('created_at')->dateTime('M d, Y g:i A')->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Add Transaction')->icon('heroicon-s-plus'),
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
