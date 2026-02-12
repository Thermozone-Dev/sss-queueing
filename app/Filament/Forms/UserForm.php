<?php

namespace App\Filament\Forms;

use App\Filament\Resources\BranchResource\RelationManagers\UsersRelationManager;
use App\Models\User;
use App\Settings\MailSettings;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserForm
{
    public static function schema(string $is_branch): array
    {
        $random = Str::random(4);

        $default_password = 'branch_admin_'.$random;
        $default_username = 'admin_'.$random;
        $default_email = $default_username.'@gmail.com';
        $default_firstname = $random.' Branch';
        $default_lastname = 'Admin';

        return [
            Forms\Components\Section::make()
                ->schema([
                    Forms\Components\Grid::make()
                        ->schema([
                            SpatieMediaLibraryFileUpload::make('media')
                                ->hidden($is_branch)
                                ->hiddenLabel()
                                ->avatar()
                                ->collection('avatars')
                                ->alignCenter()
                                ->columnSpanFull(),
                            Forms\Components\TextInput::make('username')
                                ->unique(
                                    table: 'users',
                                    column: 'username',
                                    ignoreRecord: true,
                                )
                                ->default($is_branch ? $default_username : '')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('email')
                                ->default($is_branch ? $default_email : '')
                                ->email()
                                ->unique(
                                    table: 'users',
                                    column: 'email',
                                    ignoreRecord: true,
                                )
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('firstname')
                                ->default($is_branch ? $default_firstname : '')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('lastname')
                                ->default($is_branch ? $default_lastname : '')
                                ->required()
                                ->maxLength(255),
                        ]),
                ])
                ->columnSpan([
                    'sm' => 1,
                    'lg' => 2
                ]),
            Forms\Components\Group::make()
                ->schema([
                    Forms\Components\Section::make('Role')
                        ->hidden($is_branch)
                        ->schema([
                            Select::make('roles')->label('Role')
                                ->hiddenLabel()
                                ->relationship('roles', 'name')
                                ->getOptionLabelFromRecordUsing(fn (Model $record) => Str::headline($record->name))
                                ->multiple()
                                ->preload()
                                ->maxItems(1)
                                ->native(false),
                        ])
                        ->compact(),
                    Forms\Components\Section::make()
                        ->schema([
                            Forms\Components\TextInput::make('password')
                                ->default($is_branch ? $default_password : '')
                                ->password()
                                ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                                ->dehydrated(fn (?string $state): bool => filled($state))
                                ->revealable()
                                ->required(),
                            Forms\Components\TextInput::make('passwordConfirmation')
                                ->default($is_branch ? $default_password : '')
                                ->password()
                                ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                                ->dehydrated(fn (?string $state): bool => filled($state))
                                ->revealable()
                                ->same('password')
                                ->required(),
                        ])
                        ->compact()
                        ->hidden(fn (string $operation): bool => $operation === 'edit'),
                    Forms\Components\Section::make()
                        ->schema([
                            Forms\Components\Placeholder::make('email_verified_at')
                                ->label(__('resource.general.email_verified_at'))
                                ->content(fn (User $record): ?string => $record->email_verified_at),
                            Forms\Components\Actions::make([
                                Action::make('resend_verification')
                                    ->label(__('resource.user.actions.resend_verification'))
                                    ->color('secondary')
                                    ->action(fn (MailSettings $settings, Model $record) => static::doResendEmailVerification($settings, $record)),
                            ])
                                ->hidden(fn (User $user) => $user->email_verified_at != null)
                                ->fullWidth(),
                            Forms\Components\Placeholder::make('created_at')
                                ->label(__('resource.general.created_at'))
                                ->content(fn (User $record): ?string => $record->created_at?->diffForHumans()),
                            Forms\Components\Placeholder::make('updated_at')
                                ->label(__('resource.general.updated_at'))
                                ->content(fn (User $record): ?string => $record->updated_at?->diffForHumans()),
                        ])
                        ->hidden(fn (string $operation): bool => $operation === 'create'),
                ])
                ->columnSpan(1),
        ];
    }
}

