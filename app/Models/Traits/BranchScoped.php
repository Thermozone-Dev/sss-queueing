<?php

namespace App\Models\Traits;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Builder;

trait BranchScoped
{
    protected static function bootBranchScoped(): void
    {
        // GLOBAL SCOPE (filters results)
        static::addGlobalScope('branch', function (Builder $query) {
            if (auth()->hasUser()) {
                $table = $query->getModel()->getTable();

                $query->where($table . '.branch_id', auth()->user()->branch_id);
                if(auth()->user()->branch){
                    $query->whereBelongsTo(auth()->user()->branch);
                }
            }
        });

        // AUTO-SET BRANCH ON CREATE
        static::creating(function ($model) {

            if ($model->branch_id) {
                return;
            }

            if (auth()->hasUser()) {
                $model->branch_id = auth()->user()->branch_id;
                $model->branch()->associate(auth()->user()->branch);
            }
        });
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
