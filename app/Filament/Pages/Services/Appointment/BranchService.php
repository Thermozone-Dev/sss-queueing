<?php

namespace App\Services\Appointment;

use App\Models\Branch;

class BranchService
{
    public function getBranches()
    {
        return Branch::query()
            ->with([
                'businessDay',
            ])
            ->orderBy('name')
            ->get();
    }

    public function find(int $branchId): ?Branch
    {
        return Branch::with([
            'businessDay',
        ])->find($branchId);
    }

    public function isAvailable(Branch $branch): bool
    {
        return $branch->is_active;
    }
}