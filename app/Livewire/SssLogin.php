<?php

namespace App\Livewire;

use App\Mail\SendOtpMail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class SssLogin extends Component
{
    public $step = 1;
    public $page = 'login';

    public $email = '';
    public $password = '';
    public $otp = '';



    private const OTP_TTL = 5;

    public function login(): void
    {
        $this->resetValidation();

        $this->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $this->email)->first();

        if (!$user) {
            $this->addError(
                'email',
                'No account found with this email.'
            );

            return;
        }

        if (!Hash::check($this->password, $user->password)) {
            $this->addError(
                'password',
                'Incorrect password.'
            );

            return;
        }

        $this->sendOtp();

        if ($this->getErrorBag()->has('otp')) {
            return;
        }

        $this->step = 2;
    }

    public function sendOtp(): void
    {
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

    public function verifyOtp()
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

        $user = User::where(
            'email',
            $this->email
        )->first();

        if (!$user) {
            $this->addError(
                'otp',
                'Account no longer exists.'
            );

            return;
        }

        $user->update([
            'email_verified_at' => now(),
        ]);

        Auth::login($user, true);

        session()->regenerate();

        return redirect('/admin');
    }


    private function otpKey(): string
    {
        return 'sss_login_otp_' .
            strtolower(trim($this->email));
    }

    public function render()
    {
        return view('livewire.register.registration');
    }
}
