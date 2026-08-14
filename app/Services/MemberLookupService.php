<?php

namespace App\Services;

use App\Models\APIResponse;
use App\Models\MemberDetail;
use App\Models\User;
use App\Services\Api\SssMemberApiService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MemberLookupService
{
    public function __construct(
        private SssMemberApiService $api
    ) {}

    public function lookupBySssNumber(string $sssNumber): ?array
    {
        $data = $this->api->fetchBySssNumber($sssNumber);

        if (!$data) {
            return null;
        }

        $this->logApiResponse($sssNumber, $data);

        return $data;
    }

    public function register(string $sssNumber, string $password): ?User
    {
        $data = $this->api->fetchBySssNumber($sssNumber);

        if (!$data) {
            return null;
        }

        try {
            return DB::transaction(function () use ($sssNumber, $data, $password) {
                $user = User::create([
                    'firstname' => $data['first_name'],
                    'lastname' => $data['last_name'],
                    'email' => $data['email'] ?? null,
                    'username' => $sssNumber,
                    'password' => bcrypt($password),
                ]);

                $user->assignRole('member');

                $user->memberDetail()->create([
                    'member_id' => $data['member_id'],
                    'sss_number' => $data['sss_number'],
                    'birth_date' => $data['birth_date'] ?? null,
                    'gender' => $data['gender'] ?? null,
                    'mobile_number' => $data['mobile_number'] ?? null,
                    'is_senior' => $data['is_senior'] ?? false,
                    'is_pwd' => $data['is_pwd'] ?? false,
                    'status' => $data['status'] ?? 'active',
                    'last_synced_at' => now(),
                ]);

                $this->logApiResponse($sssNumber, $data);

                return $user;
            });
        } catch (\Exception $e) {
            Log::error("Failed to register member {$sssNumber}: {$e->getMessage()}");
            return null;
        }
    }

    private function logApiResponse(string $sssNumber, array $data): void
    {
        APIResponse::where('type', 'member')->update(['is_latest' => false]);

        APIResponse::create([
            'type' => 'member',
            'url' => $this->api->getEndpoint(),
            'method' => 'GET',
            'payload' => ['sss_number' => $sssNumber],
            'response' => json_encode($data),
            'is_latest' => true,
        ]);
    }
}