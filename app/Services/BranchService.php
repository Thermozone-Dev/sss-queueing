<?php

namespace App\Services;

use App\Models\APIResponse;
use App\Models\Branch;
use App\Services\Api\SssBranchApiService;
use Illuminate\Support\Facades\Log;

class BranchService
{
    public function __construct(
        private SssBranchApiService $api
    ) {}

    public function syncFromApi(): array
    {
        $created = 0;
        $updated = 0;

        $items = $this->api->fetchBranches();

        foreach ($items as $item) {
            try {
                $branch = Branch::updateOrCreate(
                    ['code' => $item['code']],
                    $this->mapFields($item)
                );

                $branch->wasRecentlyCreated ? $created++ : $updated++;
            } catch (\Exception $e) {
                Log::error("Failed to sync branch {$item['code']}: {$e->getMessage()}");
                $failed++;
            }
        }

        $this->logApiResponse($items);

        return [
            'created' => $created,
            'updated' => $updated,
            'failed' => $failed,
        ];
    }

    private function mapFields(array $item): array
    {
        return [
            'code' => $item['code'] ?? null,
            'name' => $item['name'] ?? null,
            'opening_hours' => $item['working_hours']['start'] ?? null,
            'closing_hours' => $item['working_hours']['end'] ?? null,
            'address_line_1' => $item['address']['line1'] ?? null,
            'address_line_2' => $item['address']['line2'] ?? null,
            'city' => $item['address']['city'] ?? null,
            'province' => $item['address']['province'] ?? null,
            'postal_code' => $item['address']['postal_code'] ?? null,
            'email' => $item['email'] ?? null,
            'contact_number' => $item['contact_number'] ?? null,
            'is_active' => $item['is_active'] ?? true,
        ];
    }

    private function logApiResponse(array $items): void
    {
        APIResponse::where('type', 'branch')->update(['is_latest' => false]);

        APIResponse::create([
            'type' => 'branch',
            'url' => config('services.api.branch_url', 'https://raw.githubusercontent.com/Dennis-Enraca-School/WebSysAPI/refs/heads/main/sss/branch.json'),
            'method' => 'GET',
            'payload' => [],
            'response' => json_encode($items),
            'is_latest' => true,
        ]);
    }


}
