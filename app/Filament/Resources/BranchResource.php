<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BranchResource\Pages\CreateBranch;
use App\Filament\Resources\BranchResource\Pages\EditBranch;
use App\Filament\Resources\BranchResource\Pages\ListBranches;
use App\Filament\Resources\BranchResource\RelationManagers\BranchDisabledDateRelationManager;
use App\Filament\Resources\BranchResource\RelationManagers\BranchTransactionRelationManager;
use App\Filament\Resources\BranchResource\RelationManagers\BreaktimeRelationManager;
use App\Filament\Resources\BranchResource\RelationManagers\BusinessDayRelationManager;
use App\Filament\Resources\BranchResource\RelationManagers\UsersRelationManager;
use App\Models\APIResponse;
use App\Models\Branch;
use Filament\Forms;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Grid;
class BranchResource extends Resource
{
    protected static ?string $model = Branch::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationLabel = 'Branch Configuration';

    protected static ?string $navigationGroup = 'Administration';

    public static function form(Form $form): Form
    {
        $user = Auth::user();
        $isBranchAdmin = $user->hasRole('branch_head');



        return $form
            ->schema([
                Forms\Components\Section::make('API Branch Selection')
                    ->visible(fn (string $operation) => $operation === 'create' && ($user->hasRole('head_office') || $user->hasRole('super_admin')))
                    ->columns(1)
                    ->schema([
                        Forms\Components\Select::make('api_branch')
                            ->label('Select Branch from API')
                            ->helperText('Choose a branch to auto-fill information from API')
                            ->options(fn () => APIResponse::fetchLatestAPIRecord(1)->pluck('name','code'))
                            ->searchable()
                            ->reactive()
                            ->afterStateUpdated(fn ($state, Forms\Set $set) =>  self::autofillBranchData($state, $set)),
                    ]),

                Forms\Components\Section::make('Branch Information')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('code')
                            ->label('Branch Code')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->disabled($isBranchAdmin)
                            ->maxLength(255),

                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->disabled($isBranchAdmin)
                            ->maxLength(255),

                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('contact_number')
                            ->tel()
                            ->maxLength(20),
                    ]),

                Forms\Components\Section::make('Address')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('address_line_1')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('address_line_2')
                            ->maxLength(255),

                        Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('city')
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('province')
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('postal_code')
                                    ->maxLength(10),
                            ])
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Operating Hours')
                    ->columns(2)
                    ->schema([
                        TimePicker::make('opening_hours')
                                ->label('Opening Time')
                                ->helperText('e.g., 08:00 AM')
                                ->seconds(false)
                                ->format('H:i'),

                        TimePicker::make('closing_hours')
                            ->label('Closing Time')
                            ->helperText('e.g., 05:00 PM')
                            ->format('H:i')
                            ->seconds(false)
                            ->afterOrEqual('opening_hours'),
                    ]),

                Forms\Components\Section::make('Queue Configuration')
                    ->schema([
                        Forms\Components\CheckboxList::make('queueTypes')
                            ->label('Allowed Queue Types')
                            ->relationship('queueTypes', 'id')
                            ->options(\App\Models\QueueType::pluck('name', 'id'))
                            ->descriptions([
                                1 => 'Walk-in queue',
                                2 => 'Appointment-based queue',
                                3 => 'Priority lanes (Senior Citizens / PWD)',
                            ])
                            ->columns(3),
                    ]),

                Forms\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->disabled($isBranchAdmin),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('contact_number')
                    ->label('Contact')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status'),
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

    public static function getRelations(): array
    {
        return [
            UsersRelationManager::class,
            BusinessDayRelationManager::class,
            BreaktimeRelationManager::class,
            BranchDisabledDateRelationManager::class,
            BranchTransactionRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBranches::route('/'),
            'create' => CreateBranch::route('/create'),
            'edit' => EditBranch::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $user = Auth::user();
        $query = parent::getEloquentQuery();

        if ($user->hasRole('branch_head')) {
            return $query->where('id', $user->branch_id);
        }

        return $query;
    }

    public static function autofillBranchData($branchCode, Forms\Set $set): void
    {
        if (!$branchCode) {
            return;
        }

        $apiResponse = APIResponse::fetchLatestAPIRecord(1)->where('code', $branchCode)->first();

        if (!$apiResponse) {
            return;
        }

        $payload = $apiResponse;

        $set('code', $payload['code'] ?? null);
        $set('name', $payload['name'] ?? null);
        $set('opening_hours', $payload['opening_hours'] ?? null);
        $set('closing_hours', $payload['closing_hours'] ?? null);
        $set('address_line_1', $payload['address_line_1'] ?? null);
        $set('address_line_2', $payload['address_line_2'] ?? null);
        $set('city', $payload['city'] ?? null);
        $set('province', $payload['province'] ?? null);
        $set('postal_code', $payload['postal_code'] ?? null);
        $set('email', $payload['email'] ?? null);
        $set('contact_number', $payload['contact_number'] ?? null);
        $set('is_active', $payload['is_active'] ?? true);
    }
}
