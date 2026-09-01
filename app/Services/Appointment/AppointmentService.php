<?php

namespace App\Services\Appointment;

use App\Models\Branch;
use App\Mail\AppointmentConfirmationMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AppointmentService
{
    /**
     * Generate appointment reference.
     */
    public function generateReference(): string
    {
        return 'SSS-' . strtoupper(
            Str::random(8)
        );
    }

    /**
     * Build appointment data.
     */
    public function buildAppointmentData(
        $user,
        Branch $branch,
        array $transaction,
        string $date,
        string $time
    ): array {
        return [
            'reference' => $this->generateReference(),

            'member_name' => trim(
                ($user->firstname ?? '') . ' ' .
                    ($user->lastname ?? '')
            ),

            'email' => $user->email,

            'branch' => $branch->name,

            'branch_id' => $branch->getKey(),

            'transaction' => $transaction['name'] ?? '',

            'date' => Carbon::parse($date)
                ->format('F d, Y'),

            'time' => Carbon::createFromFormat(
                'H:i',
                $time
            )->format('h:i A'),
        ];
    }

    /**
     * Send appointment confirmation email.
     */
    public function sendConfirmation(
        string $email,
        array $appointment
    ): void {
        Mail::to($email)
            ->send(
                new AppointmentConfirmationMail($appointment)
            );
    }
}
