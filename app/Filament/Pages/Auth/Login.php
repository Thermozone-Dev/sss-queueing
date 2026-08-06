<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Form;
use Filament\Pages\Auth\Login as BasePage;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Otp;
use App\Mail\SendOtpMail;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;


class Login extends BasePage
{

    public function mount(): void
    {
        parent::mount();
    }



    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getEmailFormComponent()
                    ->label('Email'),

                $this->getPasswordFormComponent(),

                $this->getRememberFormComponent(),
            ]);
    }



    public function getHeading(): string | Htmlable
    {
        return '';
    }



    public function authenticate(): ?LoginResponse
    {

        $data = $this->form->getState();



        $user = User::where(
            'email',
            $data['email']
        )->first();



        if (
            !$user ||
            !Hash::check(
                $data['password'],
                $user->password
            )
        ) {
            return null;
        }



        // Delete old OTP
        Otp::where('user_id', $user->id)
            ->delete();



        // Generate OTP
        $code = random_int(100000, 999999);



        // Save OTP
        Otp::create([
            'user_id' => $user->id,
            'otp' => $code,
            'expires_at' => now()->addMinutes(5),
        ]);



        // Save user session
        session([
            'otp_user' => $user->id,
        ]);



        // Send OTP
        Mail::to($user->email)
            ->send(
                new SendOtpMail($code)
            );



        return new class implements LoginResponse {

            public function toResponse($request)
            {
                return redirect('/admin/verify-otp');
            }
        };
    }
}
