<div class="max-w-md mx-auto flex  justify-center items-center h-screen bg-slate-50 p-4">
    @if ($step == 1)
        <div class="flex flex-col justify-center items-center space-y-4 w-full p-2">
            <h2 class="text-xl uppercase font-semibold mb-2">
                Enter SSS Number
            </h2>


            <input type="text" wire:model="sssNumber" placeholder="12-3456789-0"
                class="border border-gray-300 p-2 rounded w-full text-center" />


            @error('sssNumber')
                <p class="text-red-500">
                    {{ $message }}
                </p>
            @enderror


            <button wire:click="verify" wire:loading.attr="disabled"
                class="bg-blue-500 text-white px-4 py-2 mt-3 text-sm rounded disabled:opacity-50 disabled:cursor-not-allowed">
                <span wire:loading.remove wire:target="verify">
                    Verify SSS
                </span>

                <span wire:loading wire:target="verify">
                    Verifying...
                </span>
            </button>
        </div>
    @endif



    @if ($step == 2)
        <div class="flex flex-col justify-center items-center space-y-4 w-full ">
            <h2 class="text-xl font-bold">
                OTP Verification
            </h2>


            <p>
                OTP sent to:
                <strong>{{ $email }}</strong>
            </p>


            <input type="text" wire:model="otp" placeholder="Enter OTP"
                class="border border-gray-300 p-2 rounded w-full text-center" />


            @error('otp')
                <p class="text-red-500">
                    {{ $message }}
                </p>
            @enderror

            <button wire:click="verifyOtp" wire:loading.attr="disabled"
                class="bg-green-500 text-white px-4 py-2 mt-3 text-sm rounded
           disabled:opacity-50 disabled:cursor-not-allowed">
                <span wire:loading.remove wire:target="verifyOtp">
                    Verify OTP
                </span>

                <span wire:loading wire:target="verifyOtp">
                    Verifying...
                </span>
            </button>
        </div>
    @endif


    @if ($step == 3)
        <div class="flex flex-col justify-center items-center space-y-4 w-full p-2">

            <h2 class="text-xl font-semibold">
                Create Password
            </h2>

            <p class="mb-4">
                Welcome,
                <strong>
                    {{ $member['first_name'] }}
                    {{ $member['last_name'] }}
                </strong>
            </p>

            <div class="space-y-3 w-full">

                <div>
                    <input type="password" wire:model="password" placeholder="Password"
                        class="border border-gray-300 p-2 rounded w-full">

                    @error('password')
                        <p class="text-red-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <input type="password" wire:model="password_confirmation" placeholder="Confirm Password"
                        class="border border-gray-300 p-2 rounded w-full">
                </div>

            </div>

            <button wire:click="savePassword" wire:loading.attr="disabled"
                class="bg-blue-500 text-white px-4 py-2 mt-3 text-sm rounded
                   disabled:opacity-50 disabled:cursor-not-allowed">
                <span wire:loading.remove wire:target="savePassword">
                    Create Account
                </span>

                <span wire:loading wire:target="savePassword">
                    Creating Account...
                </span>
            </button>

        </div>
    @endif


    @if ($step == 4)
        <div class="max-w-md mx-auto text-center py-10">

            <div class="text-6xl mb-4">
                ✅
            </div>

            <h2 class="text-3xl font-bold text-green-600 mb-2">
                Success!
            </h2>

            <p class="text-gray-600 mb-6">
                Your account has been created successfully.
            </p>

            @if (session()->has('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <a href="{{ route('filament.admin.auth.login') }}"
                class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition text-sm">
                Go to Login
            </a>

        </div>
    @endif

</div>
