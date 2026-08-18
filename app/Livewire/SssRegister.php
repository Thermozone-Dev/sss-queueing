<?php

namespace App\Livewire;

use App\Mail\SendOtpMail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class SssRegister extends Component
{
    public $step = 1;
    public $page = 'register';

    public $existingAccount = false;

    public $sssNumber = '';
    public $member = null;

    public $email = '';
    public $otp = '';

    public $password = '';
    public $password_confirmation = '';

    private const MEMBER_API =
    'https://raw.githubusercontent.com/Dennis-Enraca-School/WebSysAPI/main/sss/member.json';

    private const OTP_TTL = 5;

    public function verify(): void
    {
        $this->resetValidation();
        $this->member = null;

        $this->validate([
            'sssNumber' => ['required', 'string'],
        ]);

        $member = $this->findMember();

        if (!$member) {
            $this->addError(
                'sssNumber',
                'SSS number not found.'
            );

            return;
        }

        $this->member = $member;
        $this->email = $member['email'] ?? '';

        if (User::where('email', $this->email)->exists()) {
            $this->addError(
                'sssNumber',
                'An account already exists for this SSS number. Please login instead.'
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | CHECK EXISTING ACCOUNT
        |--------------------------------------------------------------------------
        */

        if (User::where('email', $this->email)->exists()) {
            $this->addError(
                'sssNumber',
                'An account already exists for this SSS number. Please login instead.'
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | SEND OTP
        |--------------------------------------------------------------------------
        */

        $this->sendOtp();

        if ($this->getErrorBag()->has('otp')) {
            return;
        }

        $this->step = 2;
    }

    private function findMember(): ?array
    {
        try {
            $response = Http::timeout(10)
                ->withoutVerifying()
                ->get(self::MEMBER_API);

            if (!$response->successful()) {
                $this->addError(
                    'sssNumber',
                    'Unable to fetch member data.'
                );

                return null;
            }

            $members = $response->json('data', []);

            $sssNumber = trim($this->sssNumber);

            return collect($members)->first(
                fn($member) => ($member['sss_number'] ?? null) === $sssNumber
            );
        } catch (\Throwable $e) {

            report($e);

            $this->addError(
                'sssNumber',
                'Unable to connect to the member service.'
            );

            return null;
        }
    }

    public function sendOtp(): void
    {
        if (!$this->email) {
            $this->addError(
                'otp',
                'Email address is required.'
            );

            return;
        }

        $otp = random_int(100000, 999999);

        Cache::put(
            $this->otpKey(),
            $otp,
            now()->addMinutes(self::OTP_TTL)
        );

        try {

            Mail::to($this->email)
                ->send(new SendOtpMail($otp));
        } catch (\Throwable $e) {

            Cache::forget($this->otpKey());

            report($e);

            $this->addError(
                'otp',
                'Unable to send OTP. Please try again.'
            );
        }
    }

    public function verifyOtp(): void
    {
        $this->resetValidation();

        $savedOtp = Cache::get($this->otpKey());

        if (!$savedOtp) {
            $this->addError(
                'otp',
                'OTP expired. Please request a new OTP.'
            );

            return;
        }

        if ((string) $this->otp !== (string) $savedOtp) {
            $this->addError(
                'otp',
                'Invalid OTP.'
            );

            return;
        }

        Cache::forget($this->otpKey());

        $this->step = 3;
    }

    public function savePassword()
    {
        $this->resetValidation();

        $this->validate([
            'password' => [
                'required',
                'min:8',
                'confirmed',
            ],
        ]);

        if (!$this->member) {
            $this->addError(
                'password',
                'Member information is missing.'
            );

            return;
        }

        if (User::where('email', $this->email)->exists()) {
            $this->addError(
                'password',
                'Account already exists. Please login instead.'
            );

            return;
        }

        $user = User::create([
            'username' => $this->member['sss_number'],
            'firstname' => $this->member['first_name'],
            'lastname' => $this->member['last_name'],
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'email_verified_at' => now(),
        ]);

        $user->assignRole('member');

        Auth::login($user, true);

        session()->regenerate();

        return redirect('/admin');
    }

    private function otpKey(): string
    {
        return 'sss_register_otp_' .
            strtolower(trim($this->email));
    }

    public function render()
    {
        return view('livewire.register.registration');
    }
}
