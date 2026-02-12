<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BranchResource\Pages;
use App\Filament\Resources\BranchResource\RelationManagers;
use App\Models\Branch;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BranchResource extends Resource
{
    protected static ?string $model = Branch::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Branch name')
                            ->unique()
                            ->required()
                            ->maxLength(255),
                        ]),
            ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\IconColumn::make('id')
                    ->icon(function ($state){
                        if($state == auth()->user()->branch_id){
                            return 'heroicon-o-check-circle';
                        }
                        return 'heroicon-o-x-circle';
                    })
                    ->color(function ($state){
                        if($state == auth()->user()->branch_id){
                            return 'success';
                        }
                        return 'danger';
                    })
                    ->label('Current Branch'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('switch_branch')
                    ->label('Switch Branch')
                    ->color('warning')
                    ->icon('heroicon-o-arrows-right-left')
                    ->requiresConfirmation()
                    ->modalDescription(fn($record) => 'Are you sure you want to switch your current branch from '.auth()->user()->branch->name.' to '.$record->name.'? Your dashboard will reload.')
                    ->action(function ($record) {
                        $user = auth()->user();
                        $user->branch_id = $record->id;
                        $user->save();

                        return redirect()->route('filament.admin.pages.dashboard');
                    })
                    ->visible(fn ($record) => auth()->user()->hasRole('super_admin') && auth()->user()->branch_id != $record->id),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(function (Builder $query) {

                if(auth()->user()->hasRole('super_admin')) {
                    return $query;
                }
                return $query->where('id',auth()->user()->branch_id);
            });
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\UsersRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBranches::route('/'),
            'create' => Pages\CreateBranch::route('/create'),
            'edit' => Pages\EditBranch::route('/{record}/edit'),
        ];
    }
}
