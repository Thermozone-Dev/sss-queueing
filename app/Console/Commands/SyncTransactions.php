<?php

namespace App\Console\Commands;

use App\Services\TransactionService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncTransactions extends Command
{
    protected $signature = 'sync-transactions';

    protected $description = 'Sync transactions from the SSS API';

    public function handle(TransactionService $service): int
    {
        $this->info('Syncing transactions...');

        try {
            $result = $service->syncFromApi();
        } catch (\RuntimeException $e) {
            Log::error('Transaction sync failed: ' . $e->getMessage());
            $this->error($e->getMessage());
            return self::FAILURE;
        }

        $this->info("Created: {$result['created']}");
        $this->info("Updated: {$result['updated']}");
        $this->info("Failed: {$result['failed']}");

        return self::SUCCESS;
    }
}
