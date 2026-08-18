<?php

namespace App\Services\Appointment;

class TransactionService
{
    /**
     * Available transaction types.
     */
    public function getTransactions(): array
    {
        return [
            [
                'id' => 'membership',
                'name' => 'Membership',
                'description' => 'Membership registration and account services.',
                'icon' => 'heroicon-o-user',
            ],
            [
                'id' => 'loans',
                'name' => 'Loans',
                'description' => 'Loan application and related services.',
                'icon' => 'heroicon-o-banknotes',
            ],
            [
                'id' => 'benefits',
                'name' => 'Benefits',
                'description' => 'Process your SSS benefits and claims.',
                'icon' => 'heroicon-o-document-check',
            ],
            [
                'id' => 'contribution',
                'name' => 'Contribution',
                'description' => 'Contribution and payment-related services.',
                'icon' => 'heroicon-o-credit-card',
            ],
        ];
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
