<?php

namespace App\Services\Api;

class SssMemberApiService
{
    public function fetchBySssNumber(string $sssNumber): ?array
    {
        // TODO: Replace with real api
        $json = file_get_contents(base_path('sample/member.json'));
        $response = json_decode($json, true);

        if (($response['status'] ?? null) !== 'success' || empty($response['data'])) {
            return null;
        }

        return $response['data'];
    }
}
