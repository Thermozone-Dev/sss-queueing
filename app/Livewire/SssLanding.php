<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOtpMail;


use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SssLanding extends Component
{
    public $step = 1;

    public $sssNumber = '';
    public $member = null;

    public $otp = '';
    public $email = '';

    public $password = '';
    public $password_confirmation = '';

    public function verify()
    {
        $this->resetErrorBag();
        $this->member = null;

        $this->validate([
            'sssNumber' => 'required'
        ]);

        $response = Http::withoutVerifying()->get(
            'https://raw.githubusercontent.com/Dennis-Enraca-School/WebSysAPI/main/sss/member.json'
        );

        if (!$response->successful()) {
            $this->addError('sssNumber', 'Unable to fetch member data.');
            return;
        }

        $json = $response->json();

        $members = $json['data'] ?? [];

        $member = collect($members)->first(function ($item) {
            return $item['sss_number'] === trim($this->sssNumber);
        });

        if (!$member) {
            $this->addError('sssNumber', 'SSS number not found.');
            return;
        }


        $this->member = $member;


        $this->email = $member['email'] ?? null;


        if (!$this->email) {
            $this->addError('sssNumber', 'No email registered.');
            return;
        }


        // Send OTP
        $this->sendOtp();


        $this->step = 2;
    }

    public function savePassword()
    {
        $this->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::where('email', $this->email)->first();

        if ($user) {
            $user->update([
                'password' => Hash::make($this->password),
            ]);
        } else {
            User::create([
                'username'  => $this->member['sss_number'],
                'firstname' => $this->member['first_name'],
                'lastname'  => $this->member['last_name'],
                'email'     => $this->member['email'],
                'password'  => Hash::make($this->password),
            ]);
        }

        Cache::forget('sss_otp_' . $this->email);

        session()->flash('success', 'Account created successfully.');

        $this->step = 4;
    }

    public function sendOtp()
    {
        $code = rand(100000, 999999);

        Cache::put(
            'sss_otp_' . $this->email,
            $code,
            now()->addMinutes(5)
        );

        sleep(2);

        Mail::to($this->email)
            ->send(new SendOtpMail($code));
    }

    public function verifyOtp()
    {
        $this->resetErrorBag();


        $savedOtp = Cache::get(
            'sss_otp_' . $this->email
        );


        if (!$savedOtp) {
            $this->addError('otp', 'OTP expired.');
            return;
        }


        if ($this->otp != $savedOtp) {

            $this->addError(
                'otp',
                'Invalid OTP.'
            );

            return;
        }


        $this->step = 3;
    }

    public function render()
    {
        return view('livewire.sss-landing');
    }
}
