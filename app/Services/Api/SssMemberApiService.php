<?php

namespace App\Services\Api;

use Illuminate\Support\Facades\Http;

class SssMemberApiService
{
    public function fetchMemberBySssNumber(string $sssNumber): array
    {
        // TODO: Replace with real api
        $apiUrl = config('services.api.branch_url', 'https://raw.githubusercontent.com/Dennis-Enraca-School/WebSysAPI/refs/heads/main/sss/member.json');

        $response = Http::timeout(10)->get($apiUrl);

        if (!$response->successful()) {
            throw new \RuntimeException("Member API returned status {$response->status()}");
        }

        $data = $response->json('data');

        $member = collect($data)->first(function ($item) use ($sssNumber) {
            return $item['sss_number'] === trim($sssNumber);
        });

        if (!$member) {
            return [];
        }

        if ($data === null) {
            throw new \RuntimeException('Member API response missing "data" key');
        }

        return $data;
    }


}
