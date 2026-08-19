<?php

namespace App\Services\Api;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SssBranchApiService
{
    public function fetchBranches(): Collection
    {
        // TODO: Replace with real api
        $apiUrl = config('services.api.branch_url', 'https://raw.githubusercontent.com/Dennis-Enraca-School/WebSysAPI/refs/heads/main/sss/branch.json');

        try {
            $response = Http::timeout(10)->get($apiUrl);

            if (!$response->successful()) {
                return collect();
            }

            return collect($response->json()['data'])->map(function ($item) {
                return static::mapBranchData($item);
            });

        } catch (\Exception $e) {
            Log::error('Failed to fetch branches from API: ' . $e->getMessage());
            return collect();
        }
    }


}
