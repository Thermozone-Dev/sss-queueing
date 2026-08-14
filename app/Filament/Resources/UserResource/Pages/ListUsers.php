<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListUsers extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return static::$resource::getWidgets();
    }

    public function getTabs(): array
    {
        $user = auth()->user();
        $tabs = [
            null => Tab::make('All'),
            'admin' => Tab::make()->query(fn ($query) => $query->with('roles')->whereRelation('roles', 'name', '=', 'admin')),
            'branch_head' => Tab::make()->query(fn ($query) => $query->with('roles')->whereRelation('roles', 'name', '=', 'branch_head')),
            'branch_staff' => Tab::make()->query(fn ($query) => $query->with('roles')->whereRelation('roles', 'name', '=', 'branch_staff')),
            'member' => Tab::make()->query(fn ($query) => $query->with('roles')->whereRelation('roles', 'name', '=', 'member')),
        ];


        if ($user->hasRole('head_office') || $user->isSuperAdmin()) {
            $tabs['head_office'] = Tab::make()->query(fn ($query) => $query->with('roles')->whereRelation('roles', 'name', '=', 'head_office'));

        }

        if ($user->isSuperAdmin()) {
            $tabs['superadmin'] = Tab::make()->query(fn ($query) => $query->with('roles')->whereRelation('roles', 'name', '=', config('filament-shield.super_admin.name')));
        }

        return $tabs;
    }

    protected function getTableQuery(): Builder
    {
        $user = auth()->user();
        $model = (new (static::$resource::getModel()))->with('roles')->where('id', '!=', auth()->user()->id);

        if (!$user->isSuperAdmin()) {
            $model = $model->whereDoesntHave('roles', function ($query) {
                $query->where('name', '=', config('filament-shield.super_admin.name'));
            });
        }

        return $model;
    }
}
