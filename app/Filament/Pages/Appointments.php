<?php

namespace App\Filament\Pages;

use App\Services\Appointment\AppointmentService;
use App\Services\Appointment\BranchService;
use App\Services\Appointment\ScheduleService;
use App\Services\Appointment\TransactionService;
use Filament\Pages\Page;

class Appointments extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static string $view =
    'filament.pages.appointments';


    public int $step = 1;

    public array $branches = [];

    public ?array $selectedBranch = null;

    public ?string $selectedTransaction = null;

    public ?string $selectedDate = null;

    public ?string $selectedTime = null;

    public array $timeSlots = [];



    public array $transactions = [];



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
        $this->branches =
            $this->branchService()->getBranches();

        $this->transactions =
            $this->transactionService()->getTransactions();
    }



    public function getHeading(): string
    {
        return '';
    }



    public function selectBranch(string $branchId): void
    {
        $branch = $this->branchService()->find(
            $branchId,
            $this->branches
        );

        if (!$branch) {
            return;
        }

        if (!$this->branchService()->isAvailable($branch)) {
            return;
        }

        $this->selectedBranch = $branch;

        $this->selectedTransaction = null;
        $this->selectedDate = null;
        $this->selectedTime = null;
        $this->timeSlots = [];

        $this->step = 2;
    }



    public function selectTransaction(string $transaction): void
    {
        if (!$this->transactionService()->exists(
            $transaction,
            $this->transactions
        )) {
            return;
        }

        $this->selectedTransaction = $transaction;

        $this->selectedDate = null;
        $this->selectedTime = null;
        $this->timeSlots = [];

        $this->step = 3;
    }


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
        $this->selectedTime = null;

        $this->timeSlots =
            $this->scheduleService()->generateTimeSlots(
                $this->selectedBranch
            );

        $this->step = 4;
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



    public function backToBranch(): void
    {
        $this->step = 1;

        $this->selectedBranch = null;
        $this->selectedTransaction = null;
        $this->selectedDate = null;
        $this->selectedTime = null;
        $this->timeSlots = [];
    }


    public function backToTransaction(): void
    {
        $this->step = 2;

        $this->selectedTransaction = null;
        $this->selectedDate = null;
        $this->selectedTime = null;
        $this->timeSlots = [];
    }


    public function backToDate(): void
    {
        $this->step = 3;

        $this->selectedTime = null;
        $this->timeSlots = [];

        if ($this->selectedBranch && $this->selectedDate) {
            $this->timeSlots =
                $this->scheduleService()->generateTimeSlots(
                    $this->selectedBranch
                );
        }
    }


    public function confirmAppointment(): void
    {
        if (
            !$this->selectedBranch ||
            !$this->selectedTransaction ||
            !$this->selectedDate ||
            !$this->selectedTime
        ) {
            return;
        }

        $user = auth()->user();

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

        $this->step = 5;
    }
}
