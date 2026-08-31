<?php

namespace App\Filament\Pages;

use App\Models\Branch;
use App\Services\Appointment\AppointmentService;
use App\Services\Appointment\BranchService;
use App\Services\Appointment\ScheduleService;
use App\Services\Appointment\TransactionService;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class Appointments extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static string $view = 'filament.pages.appointments';

    /*
    |--------------------------------------------------------------------------
    | Appointment Steps
    |--------------------------------------------------------------------------
    |
    | Step 1 = Branch + Transaction
    | Step 2 = Date + Time
    | Step 3 = Confirmation
    |
    */

    public int $step = 1;

    public string $branchSearch = '';

    public $branches = [];

    public ?Branch $selectedBranch = null;

    public ?string $selectedTransaction = null;

    public ?string $selectedDate = null;

    public ?string $calendarMonth = null;

    public ?string $selectedTime = null;

    public bool $agreedToPolicies = false;

    public array $timeSlots = [];

    public $transactions = [];

    protected function branchService(): BranchService
    {
        return app(BranchService::class);
    }

    protected function transactionService(): TransactionService
    {
        return app(TransactionService::class);
    }

    protected function scheduleService(): ScheduleService
    {
        return app(ScheduleService::class);
    }

    protected function appointmentService(): AppointmentService
    {
        return app(AppointmentService::class);
    }

    public function mount(): void
    {
        $this->branches = $this->branchService()->getBranches();

        $this->transactions = [];
        $this->calendarMonth = now()->format('Y-m');
    }

    public function getHeading(): string
    {
        return '';
    }

    /*
    |--------------------------------------------------------------------------
    | Reset Helpers
    |--------------------------------------------------------------------------
    */

    private function clearAppointmentSelection(): void
    {
        $this->selectedTransaction = null;
        $this->selectedDate = null;
        $this->selectedTime = null;
        $this->agreedToPolicies = false;
        $this->timeSlots = [];
    }

    private function clearDateTimeSelection(): void
    {
        $this->selectedDate = null;
        $this->selectedTime = null;
        $this->agreedToPolicies = false;
        $this->timeSlots = [];
    }

    private function clearBranchSelection(): void
    {
        $this->selectedBranch = null;
        $this->transactions = [];

        $this->clearAppointmentSelection();
    }

    /*
    |--------------------------------------------------------------------------
    | Branch Search
    |--------------------------------------------------------------------------
    */

    public function getFilteredBranchesProperty()
    {
        $search = strtolower(trim($this->branchSearch));

        if ($search === '') {
            return collect($this->branches);
        }

        return collect($this->branches)
            ->filter(function ($branch) use ($search) {
                $value = strtolower(
                    ($branch->name ?? '') . ' ' .
                    ($branch->city ?? '') . ' ' .
                    ($branch->province ?? '') . ' ' .
                    ($branch->address_line_1 ?? '') . ' ' .
                    ($branch->address_line_2 ?? '')
                );

                return str_contains($value, $search);
            })
            ->values();
    }

    /*
    |--------------------------------------------------------------------------
    | STEP 1
    | Branch + Transaction
    |--------------------------------------------------------------------------
    */

    public function selectBranch(int $branchId): void
    {
        $branch = $this->branchService()->find($branchId);

        if (!$branch) {
            return;
        }

        if (!$this->branchService()->isAvailable($branch)) {
            return;
        }

        $this->selectedBranch = $branch;

        $this->transactions =
            $this->transactionService()->getTransactions($branch);

        // Reset transaction, date and time
        $this->clearAppointmentSelection();

        // Stay on Step 1
        $this->step = 1;
    }

    public function selectTransaction(string $transactionId): void
    {
        if (!$this->selectedBranch) {
            return;
        }

        if (
            !$this->transactionService()->exists(
                $transactionId,
                $this->transactions
            )
        ) {
            return;
        }

        $this->selectedTransaction = $transactionId;

        // Reset date and time only
        $this->clearDateTimeSelection();

        // Stay on Step 1
        $this->step = 1;
    }

    public function continueToDateTime(): void
    {
        if (
            !$this->selectedBranch ||
            !$this->selectedTransaction
        ) {
            return;
        }

        $this->step = 2;
    }

    /*
    |--------------------------------------------------------------------------
    | STEP 2
    | Date + Time
    |--------------------------------------------------------------------------
    */

    public function selectDate(string $date): void
    {
        if (!$this->selectedBranch) {
            return;
        }

        $isOperatingDay =
            $this->scheduleService()->isOperatingDay(
                $date,
                $this->selectedBranch
            );

        if (!$isOperatingDay) {
            $this->selectedDate = null;
            $this->selectedTime = null;
            $this->timeSlots = [];

            return;
        }

        $this->selectedDate = $date;
        $this->calendarMonth = \Carbon\Carbon::parse($date)->format('Y-m');
        $this->selectedTime = null;

        $this->timeSlots =
            $this->scheduleService()->generateTimeSlots(
                $this->selectedBranch
            );

        $this->step = 2;
    }

    public function previousMonth(): void
    {
        $this->calendarMonth = \Carbon\Carbon::parse(
            $this->calendarMonth ?? now()->format('Y-m')
        )->subMonth()->format('Y-m');
    }

    public function nextMonth(): void
    {
        $this->calendarMonth = \Carbon\Carbon::parse(
            $this->calendarMonth ?? now()->format('Y-m')
        )->addMonth()->format('Y-m');
    }

    public function selectTime(string $time): void
    {
        $slot =
            $this->scheduleService()->findTimeSlot(
                $time,
                $this->timeSlots
            );

        if (!$slot || !$slot['available']) {
            return;
        }

        $this->selectedTime = $time;
    }

    public function continueToConfirmation(): void
    {
        if (
            !$this->selectedBranch ||
            !$this->selectedTransaction ||
            !$this->selectedDate ||
            !$this->selectedTime
        ) {
            return;
        }

        $this->step = 3;
    }

    /*
    |--------------------------------------------------------------------------
    | Navigation
    |--------------------------------------------------------------------------
    */

    public function backToBranch(): void
    {
        $this->step = 1;

        $this->clearBranchSelection();
    }

    public function backToDateTime(): void
    {
        $this->step = 2;

        $this->selectedTime = null;
        $this->timeSlots = [];

        if (
            $this->selectedBranch &&
            $this->selectedDate
        ) {
            $this->timeSlots =
                $this->scheduleService()->generateTimeSlots(
                    $this->selectedBranch
                );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | STEP 3
    | Confirm Appointment
    |--------------------------------------------------------------------------
    */

    public function confirmAppointment(): void
    {
        if (
            !$this->selectedBranch ||
            !$this->selectedTransaction ||
            !$this->selectedDate ||
            !$this->selectedTime ||
            !$this->agreedToPolicies
        ) {
            if (!$this->agreedToPolicies) {
                $this->addError('policies', 'You must agree to the appointment policies before submitting.');
            }

            return;
        }

        $user = Auth::user();

        if (!$user || !$user->email) {
            $this->addError(
                'appointment',
                'No email address is associated with your account.'
            );

            return;
        }

        $transaction =
            $this->transactionService()->find(
                $this->selectedTransaction,
                $this->transactions
            );

        if (!$transaction) {
            return;
        }

        $appointment =
            $this->appointmentService()->buildAppointmentData(
                $user,
                $this->selectedBranch,
                $transaction,
                $this->selectedDate,
                $this->selectedTime
            );

        try {
            $this->appointmentService()->sendConfirmation(
                $user->email,
                $appointment
            );
        } catch (\Throwable $e) {
            report($e);

            $this->addError(
                'appointment',
                'The appointment was not completed because the confirmation email could not be sent.'
            );

            return;
        }

        // Appointment successfully confirmed
        $this->step = 4;
    }
}
