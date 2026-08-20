<?php

namespace App\Services;

use App\Models\APIResponse;
use App\Models\User;
use App\Services\Api\SssMemberApiService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MemberService
{
    public function __construct(
        private SssMemberApiService $api
    )
    {
    }

    public function lookupBySssNumber(string $sssNumber): ?array
    {
        $data = $this->api->fetchMemberBySssNumber($sssNumber);

        if (!$data) {
            return null;
        }

        $this->logApiResponse($sssNumber, $data);

        return $data;
    }

    private function logApiResponse(string $sssNumber, array $data): void
    {
        APIResponse::where('type', 'member')->update(['is_latest' => false]);

        APIResponse::create([
            'type' => 'member',
            'url' => config('services.api.branch_url', 'https://raw.githubusercontent.com/Dennis-Enraca-School/WebSysAPI/refs/heads/main/sss/member.json'),
            'method' => 'GET',
            'payload' => ['sss_number' => $sssNumber],
            'response' => json_encode($data),
            'is_latest' => true,
        ]);
    }
}
