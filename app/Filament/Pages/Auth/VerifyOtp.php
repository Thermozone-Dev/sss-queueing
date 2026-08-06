<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class VerifyOtp extends Component
{
    public $otp = '';

    public $password = '';
    public $password_confirmation = '';

    public bool $verified = false;

    public function verify()
    {
        $otpRecord = Otp::where('user_id', session('otp_user'))
            ->where('otp', $this->otp)
            ->where('expires_at', '>', now())
            ->first();

        if (! $otpRecord) {
            $this->addError('otp', 'Invalid OTP.');
            return;
        }

        // OTP verified successfully
        $this->verified = true;
    }

    public function savePassword()
    {
        $this->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::findOrFail(session('otp_user'));

        $user->update([
            'password' => Hash::make($this->password),
        ]);

        // Delete OTP
        Otp::where('user_id', $user->id)->delete();

        // Remove temporary session
        session()->forget('otp_user');

        // Login user
        Auth::login($user);

        return redirect('/admin');
    }

    public function render()
    {
        return view('livewire.verify-otp');
    }
}
