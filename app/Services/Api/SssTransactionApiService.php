<?php

namespace App\Services\Api;

use Illuminate\Support\Facades\Http;

class SssTransactionApiService
{
    public function fetchTransactions(): array
    {
        // TODO: Replace with real api
        $apiUrl = config('services.api.branch_url', 'https://raw.githubusercontent.com/Dennis-Enraca-School/WebSysAPI/refs/heads/main/sss/transaction.json');

        $response = Http::timeout(10)->get($apiUrl);

        if (!$response->successful()) {
            throw new \RuntimeException("Transaction API returned status {$response->status()}");
        }

        $data = $response->json('data');

        if ($data === null) {
            throw new \RuntimeException('Transaction API response missing "data" key');
        }

        return $data;
    }


}
