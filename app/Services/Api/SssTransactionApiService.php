<?php

namespace App\Services\Api;

use Illuminate\Support\Facades\Http;

class SssTransactionApiService
{
    public function fetchTransactions(): array
    {
        $apiUrl = config('services.sss_api.base_url') . '/transaction.json';

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
