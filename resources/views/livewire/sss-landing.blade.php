<div class="max-w-md mx-auto mt-10">
    <div>

        @if ($step == 1)
            <h2 class="text-xl font-bold">
                Enter SSS Number
            </h2>


            <input type="text" wire:model="sssNumber" placeholder="12-3456789-0" class="border p-2 rounded w-full" />


            @error('sssNumber')
                <p class="text-red-500">
                    {{ $message }}
                </p>
            @enderror


            <button wire:click="verify" class="bg-blue-500 text-white px-4 py-2 mt-3 rounded">
                Verify SSS
            </button>
        @endif



        @if ($step == 2)
            <h2 class="text-xl font-bold">
                OTP Verification
            </h2>


            <p>
                OTP sent to:
                <strong>{{ $email }}</strong>
            </p>


            <input type="text" wire:model="otp" placeholder="Enter OTP" class="border p-2 rounded" />


            @error('otp')
                <p class="text-red-500">
                    {{ $message }}
                </p>
            @enderror


            <button wire:click="verifyOtp" class="bg-green-500 text-white px-4 py-2 mt-3 rounded">

                Verify OTP

            </button>
        @endif
        @if ($step == 3)
            <h2 class="text-xl font-bold">
                Create Password
            </h2>

            <p class="mb-4">
                Welcome,
                <strong>{{ $member['first_name'] }} {{ $member['last_name'] }}</strong>
            </p>

            <div class="space-y-3">

                <div>
                    <input type="password" wire:model="password" placeholder="Password"
                        class="border p-2 rounded w-full">

                    @error('password')
                        <p class="text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <input type="password" wire:model="password_confirmation" placeholder="Confirm Password"
                        class="border p-2 rounded w-full">
                </div>

            </div>

            <button wire:click="savePassword" class="bg-blue-600 text-white px-4 py-2 mt-4 rounded">

                Create Account

            </button>
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
                    class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition">
                    Go to Login
                </a>

            </div>
        @endif

    </div>
</div>
