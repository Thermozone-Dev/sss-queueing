<?php

namespace App\Services\Appointment;

use App\Models\Branch;

class TransactionService
{
    /**
     * Available transaction types.
     */
    public function getTransactions(Branch $branch): array
    {
        return $branch->branchTransactions()
            ->where('is_active', true)
            ->with('transaction')
            ->get()
            ->filter(fn ($branchTransaction) => $branchTransaction->transaction !== null)
            ->map(fn ($branchTransaction) => [
                'id' => $branchTransaction->transaction->id,
                'name' => $branchTransaction->transaction->name,
                'description' => $branchTransaction->transaction->description,
                'icon' => 'heroicon-o-credit-card',
            ])
            ->values()
            ->all();
    }

    /**
     * Check if transaction exists.
     */
    public function exists(string $transaction, array $transactions): bool
    {
        return collect($transactions)
            ->contains('id', $transaction);
    }

    /**
     * Get transaction by ID.
     */
    public function find(
        string $transaction,
        array $transactions
    ): ?array {
        return collect($transactions)
            ->firstWhere('id', $transaction);
    }
}
