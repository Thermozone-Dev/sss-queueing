<?php

namespace App\Services;

use App\Models\APIResponse;
use App\Models\Transaction;
use App\Services\Api\SssTransactionApiService;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class TransactionSyncService
{
    public function __construct(
        private SssTransactionApiService $api
    ) {}

    public function syncAll(): array
    {
        $counts = ['created' => 0, 'updated' => 0, 'failed' => 0];
        $allItems = $this->api->fetchAll();

        foreach ($allItems as $item) {
            try {
                $transaction = Transaction::withoutGlobalScopes()->updateOrCreate(
                    ['transaction_id_api' => $item['transaction_id']],
                    $this->mapFields($item)
                );

                $transaction->wasRecentlyCreated ? $counts['created']++ : $counts['updated']++;
            } catch (\Exception $e) {
                Log::error("Failed to sync transaction {$item['transaction_id']}: {$e->getMessage()}");
                $counts['failed']++;
            }
        }

        $this->logApiResponse($allItems);

        return $counts;
    }

    public function syncOne(string $transactionId): Transaction
    {
        $item = $this->api->fetchById($transactionId);

        if (!$item) {
            throw new RuntimeException("Transaction {$transactionId} not found in API");
        }

        $transaction = Transaction::withoutGlobalScopes()->updateOrCreate(
            ['transaction_id_api' => $item['transaction_id']],
            $this->mapFields($item)
        );

        $this->logApiResponse([$item]);

        return $transaction;
    }

    private function mapFields(array $item): array
    {
        return [
            'name' => $item['name'],
            'code' => $item['code'],
            'description' => $item['description'] ?? null,
            'category' => $item['category'] ?? null,
            'is_active' => ($item['status'] ?? 'active') === 'active',
        ];
    }

    private function logApiResponse(array $items): void
    {
        APIResponse::where('type', 'transaction')->update(['is_latest' => false]);

        APIResponse::create([
            'type' => 'transaction',
            'url' => $this->api->getEndpoint(),
            'method' => 'GET',
            'payload' => [],
            'response' => json_encode($items),
            'is_latest' => true,
        ]);
    }
}