<?php

namespace App\Services\Api;

use Illuminate\Support\Facades\Http;

class SssBranchApiService
{
    public function fetchBranches(): array
    {
        $apiUrl = config('services.sss_api.base_url') . '/branch.json';

        $response = Http::timeout(10)->get($apiUrl);

        if (!$response->successful()) {
            throw new \RuntimeException("Branch API returned status {$response->status()}");
        }

        $data = $response->json('data');

        if ($data === null) {
            throw new \RuntimeException('Branch API response missing "data" key');
        }

        return $data;
    }


}
