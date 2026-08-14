<?php

namespace App\Services;

use App\Models\APIResponse;
use App\Models\Branch;
use App\Services\Api\SssBranchApiService;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class BranchSyncService
{
    public function __construct(
        private SssBranchApiService $api
    ) {}

    public function syncAll(): array
    {
        $counts = ['created' => 0, 'updated' => 0, 'failed' => 0];
        $allItems = $this->api->fetchAll();

        foreach ($allItems as $item) {
            try {
                $branch = Branch::updateOrCreate(
                    ['code' => $item['code']],
                    $this->mapFields($item)
                );

                $branch->wasRecentlyCreated ? $counts['created']++ : $counts['updated']++;
            } catch (\Exception $e) {
                Log::error("Failed to sync branch {$item['code']}: {$e->getMessage()}");
                $counts['failed']++;
            }
        }

        $this->logApiResponse($allItems);

        return $counts;
    }

    public function syncOne(string $branchCode): Branch
    {
        $item = $this->api->fetchById($branchCode);

        if (!$item) {
            throw new RuntimeException("Branch {$branchCode} not found in API");
        }

        $branch = Branch::updateOrCreate(
            ['code' => $item['code']],
            $this->mapFields($item)
        );

        $this->logApiResponse([$item]);

        return $branch;
    }

    private function mapFields(array $item): array
    {
        return [
            'name' => $item['name'],
            'address_line_1' => $item['address']['line1'] ?? null,
            'city' => $item['address']['city'] ?? null,
            'province' => $item['address']['province'] ?? null,
            'postal_code' => $item['address']['postal_code'] ?? null,
            'contact_number' => $item['contact_number'] ?? null,
            'email' => $item['email'] ?? null,
            'opening_hours' => $item['working_hours']['start'] ?? null,
            'closing_hours' => $item['working_hours']['end'] ?? null,
            'is_active' => ($item['status'] ?? 'active') === 'active',
        ];
    }

    private function logApiResponse(array $items): void
    {
        APIResponse::where('type', 'branch')->update(['is_latest' => false]);

        APIResponse::create([
            'type' => 'branch',
            'url' => $this->api->getEndpoint(),
            'method' => 'GET',
            'payload' => [],
            'response' => json_encode($items),
            'is_latest' => true,
        ]);
    }
}
