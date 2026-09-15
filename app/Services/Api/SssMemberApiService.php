<?php

namespace App\Services\Api;

use Illuminate\Support\Facades\Http;

class SssMemberApiService
{
    public function fetchMemberBySssNumber(string $sssNumber): array
    {
        $apiUrl = config('services.sss_api.base_url') . '/member.json';

        $response = Http::timeout(10)->get($apiUrl);

        if (!$response->successful()) {
            throw new \RuntimeException("Member API returned status {$response->status()}");
        }

        $data = $response->json('data');

        if ($data === null) {
            throw new \RuntimeException('Member API response missing "data" key');
        }

        $member = collect($data)->first(function ($item) use ($sssNumber) {
            return $item['sss_number'] === trim($sssNumber);
        });

        if (!$member) {
            return [];
        }

        return $member;
    }


}
