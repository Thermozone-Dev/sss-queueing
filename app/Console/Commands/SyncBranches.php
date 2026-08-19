<?php

namespace App\Console\Commands;

use App\Services\BranchService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncBranches extends Command
{
    protected $signature = 'sync-branches';

    protected $description = 'Sync branches from the SSS API';

    public function handle(BranchService $service): int
    {
        $this->info('Syncing branches...');

        try {
            $result = $service->syncFromApi();
        } catch (\RuntimeException $e) {
            Log::error('Branch sync failed: ' . $e->getMessage());
            $this->error($e->getMessage());
            return self::FAILURE;
        }

        $this->info("Created: {$result['created']}");
        $this->info("Updated: {$result['updated']}");
        $this->info("Failed: {$result['failed']}");

        return self::SUCCESS;
    }
}
