<?php

namespace App\Services;

use App\Models\APIResponse;
use App\Models\Transaction;
use App\Services\Api\SssTransactionApiService;
use Illuminate\Support\Facades\Log;

class TransactionService
{
    public function __construct(
        private SssTransactionApiService $api
    ) {}

    public function syncFromApi(): array
    {
        $created = 0;
        $updated = 0;
        $failed = 0;

        $items = $this->api->fetchTransactions();

        foreach ($items as $item) {
            try {
                $transaction = Transaction::updateOrCreate(
                    [
                        'code' => $item['code'],
                        'transaction_id_api' => $item['transaction_id_api']
                    ],
                    $this->mapFields($item)
                );

                $transaction->wasRecentlyCreated ? $created++ : $updated++;
            } catch (\Exception $e) {
                Log::error("Failed to sync transaction {$item['code']}: {$e->getMessage()}");
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
            'transaction_id_api' => $item['transaction_id'] ?? null,
            'code' => $item['code'] ?? null,
            'name' => $item['name'] ?? null,
            'description' => $item['description'] ?? null,
            'category' => $item['category'] ?? null,
        ];
    }

    private function logApiResponse(array $items): void
    {
        APIResponse::where('type', 'transaction')->update(['is_latest' => false]);

        APIResponse::create([
            'type' => 'transaction',
            'url' => config('services.api.branch_url', 'https://raw.githubusercontent.com/Dennis-Enraca-School/WebSysAPI/refs/heads/main/sss/transaction.json'),
            'method' => 'GET',
            'payload' => [],
            'response' => json_encode($items),
            'is_latest' => true,
        ]);
    }


}
