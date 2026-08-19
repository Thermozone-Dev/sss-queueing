<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class APIResponse extends Model
{
    protected $table = 'api_responses';

    protected $fillable = [
        'type',
        'payload',
        'response',
        'is_latest',
        'method',
        'status',
        'url',
    ];

    protected $casts = [
        'payload' => 'array',
        'is_latest' => 'boolean',
        'type' => 'string',
        'status' => 'string',
        'response' => 'string',
        'method' => 'string',
        'url' => 'string',
    ];

    public static function fetchLatestAPIRecord($type)
    {

        switch ($type) {
            case 1:
                return static::fetchBranchesFromAPI();
            case 2:
                return static::fetchTransactionFromAPI();
            case 'member':
                return $payload;
            default:
                throw new \Exception("Invalid API type: {$type}. Supported types are: 1 - branch, 2 - transaction, 3 - member");
        }
    }

    public static function mapBranchData($response)
    {
        return [
            'code' => $response['code'] ?? null,
            'name' => $response['name'] ?? null,
            'opening_hours' => $response['working_hours']['start'] ?? null,
            'closing_hours' => $response['working_hours']['end'] ?? null,
            'address_line_1' => $response['address']['line1'] ?? null,
            'address_line_2' => $response['address']['line2'] ?? null,
            'city' => $response['address']['city'] ?? null,
            'province' => $response['address']['province'] ?? null,
            'postal_code' => $response['address']['postal_code'] ?? null,
            'email' => $response['email'] ?? null,
            'contact_number' => $response['contact_number'] ?? null,
            'is_active' => $response['is_active'] ?? true,
        ];
    }

    public static function fetchBranchesFromAPI()
    {
        $apiUrl = config('services.api.branch_url', 'https://raw.githubusercontent.com/Dennis-Enraca-School/WebSysAPI/refs/heads/main/sss/branch.json');

        try {
            $response = Http::timeout(10)->get($apiUrl);

            if (!$response->successful()) {
                return collect();
            }
            return collect($response->json()['data'])->map(function ($item) {
                return static::mapBranchData($item);
            });


        } catch (\Exception $e) {
            Log::error('Failed to fetch branches from API: ' . $e->getMessage());
            return collect();
        }
    }


    public static function fetchTransactionFromAPI()
    {
        $apiUrl = config('services.api.branch_url', 'https://raw.githubusercontent.com/Dennis-Enraca-School/WebSysAPI/refs/heads/main/sss/transaction.json');

        try {
            $response = Http::timeout(10)->get($apiUrl);
            if (!$response->successful()) {
                return collect();
            }
            return collect($response->json()['data'])->map(function ($item) {
                $mapped_result = static::mapTransactionData($item);

                return $mapped_result;
            });


        } catch (\Exception $e) {
            Log::error('Failed to fetch branches from API: ' . $e->getMessage());
            return collect();
        }
    }


    public static function mapTransactionData($response)
    {
        return [
            'transaction_id_api' => $response['transaction_id'] ?? null,
            'code' => $response['code'] ?? null,
            'name' => $response['name'] ?? null,
            'description' => $response['description'] ?? null,
            'category' => $response['category'] ?? null,
        ];
    }

    private function mapMemberData($payload)
    {
        return $payload;
    }

}
