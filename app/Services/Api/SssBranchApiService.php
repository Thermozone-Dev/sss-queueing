<?php

namespace App\Services\Api;

class SssBranchApiService
{
    public function fetchAll(): array
    {
        // TODO: Replace with real api
        $json = file_get_contents(base_path('sample/branch.json'));
        $response = json_decode($json, true);

        return $response['data'] ?? [];
    }

    public function fetchById(string $branchCode): ?array
    {
        foreach ($this->fetchAll() as $branch) {
            if ($branch['branch_id'] === $branchCode) {
                return $branch;
            }
        }

        return null;
    }

    public function getEndpoint(): string
    {
        return config('services.sss.base_url') . '/branches';
    }
}
