<?php

namespace App\Services\Appointment;

use Illuminate\Support\Facades\Http;

class BranchService
{
    protected string $branchUrl =
    'https://raw.githubusercontent.com/Dennis-Enraca-School/WebSysAPI/main/sss/branch.json';

    /**
     * Get all branches.
     */
    public function getBranches(): array
    {
        $response = Http::get($this->branchUrl);

        if (!$response->successful()) {
            return [];
        }

        return $response->json('data', []);
    }

    /**
     * Find branch by branch ID.
     */
    public function find(string $branchId, array $branches): ?array
    {
        return collect($branches)
            ->firstWhere('branch_id', $branchId);
    }

    /**
     * Check if branch can accept appointments.
     */
    public function isAvailable(array $branch): bool
    {
        return ($branch['appointment_enabled'] ?? false) === true &&
            ($branch['status'] ?? null) === 'active';
    }
}
