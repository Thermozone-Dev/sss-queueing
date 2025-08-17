<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-path-rounded-square';

    public static function form(Form $form): Form
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
                                Select::make('station_id')
                                    ->searchable()
                                    ->preload()
                                    ->label('Assigned Station')
                                    ->relationship('station', 'name'),
                                Select::make('users')
                                    ->label('Assigned Staff')
                                    ->preload()
                                    ->getOptionLabelFromRecordUsing(fn (Model $record) => $record->fullname)
                                    ->relationship(
                                        name: 'users',
                                        modifyQueryUsing: fn (\Illuminate\Database\Eloquent\Builder $query) =>
                                        $query->role('staff')
                                    ),
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
                                Textarea::make('description')->rows(3),
                                Toggle::make('is_required')->label('Required?')->default(false),
                            ])
                            ->orderable('sort_order')
                            ->defaultItems(0)
                            ->addActionLabel('Add Step')
                            ->columns(1),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('code')->sortable(),
                TextColumn::make('description')->sortable(),
                TextColumn::make('station.name')->label('Assigned Station')->sortable(),
                TextColumn::make('created_at')->dateTime('M d, Y g:i A')->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        $user = auth()->user();

        return $user?->can('view_any_transaction') || $user?->can('assigned_transaction');
    }
}
