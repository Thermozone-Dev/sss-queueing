<?php

namespace App\Filament\Resources\BranchResource\RelationManagers;

use App\Filament\Resources\UserResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Table;
use GuzzleHttp\Promise\Create;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UsersRelationManager extends RelationManager
{
    protected static string $relationship = 'users';

    public function form(Form $form): Form
    {
        return UserResource::form($form);
    }

    public function table(Table $table): Table
    {
        /** Pending Task
         * On the user management the branch_head is not allowed to create and assign a head_office account and super_admin kindly apply also on user resource
         * the head_office account cant assign and add super_admin role
         * The created account on this relation manager automatically ties on parent branch record
         */
        $user = Auth::user();
        $isBranchAdmin = $user->hasRole('branch_head');
        $isHeadOffice = $user->hasRole('head_office');

        return $table
            ->modifyQueryUsing(fn ($query) => $query->withoutGlobalScopes())
            ->recordTitleAttribute('users')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->getStateUsing(fn ($record) => $record->firstname . ' ' . $record->lastname),
                SpatieMediaLibraryImageColumn::make('media')->label('Avatar')
                    ->collection('avatars')
                    ->wrap(),
                Tables\Columns\TextColumn::make('username')->label('Username')
                    ->description(fn (Model $record) => $record->firstname.' '.$record->lastname)
                    ->searchable(),
                Tables\Columns\TextColumn::make('roles.name')->label('Role')
                    ->formatStateUsing(fn ($state): string => Str::headline($state))
                    ->colors(['info'])
                    ->badge(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')->label('Verified at')
                    ->dateTime()
                    ->sortable(),
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
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->visible(!$isBranchAdmin)
                    ->using(function (array $data, string $model): Model {

                        $branch = $this->getOwnerRecord();
                        $data['branch_id'] = $branch->id;
                        $data['email_verified_at'] = now();

                        return $model::create($data);
                    })
                    ->form(fn (Form $form) => $this->getRestrictedUserForm($form))
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(!$isBranchAdmin)
                    ->form(fn (Form $form) => $this->getRestrictedUserForm($form)),
                Tables\Actions\DeleteAction::make()
                    ->visible(!$isBranchAdmin),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(!$isBranchAdmin),
                ]),
            ]);

        return $table;
    }

    private function getRestrictedUserForm(Form $form)
    {
        return UserResource::form($form);
    }
}
