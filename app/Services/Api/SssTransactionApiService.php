<?php

namespace App\Services\Api;

class SssTransactionApiService
{
    public function fetchAll(): array
    {
        // TODO: Replace with real api
        $json = file_get_contents(base_path('sample/transaction.json'));
        $response = json_decode($json, true);

        return $response['data'] ?? [];
    }

    public function fetchById(string $transactionCode): ?array
    {
        foreach ($this->fetchAll() as $transaction) {
            if ($transaction['transaction_id'] === $transactionCode) {
                return $transaction;
            }
        }

        return null;
    }

    public function getEndpoint(): string
    {
        return config('services.sss.base_url') . '/transactions';
    }
}
