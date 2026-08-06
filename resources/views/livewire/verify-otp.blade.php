<div class="max-w-md mx-auto mt-20">

    @if (!$verified)
        <h2 class="text-xl font-bold mb-5">
            Enter OTP
        </h2>

        <input type="text" wire:model="otp" class="border rounded p-2 w-full" placeholder="6 digit OTP">

        @error('otp')
            <span class="text-red-500 text-sm">
                {{ $message }}
            </span>
        @enderror

        <button wire:click="verify" class="mt-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
            Verify OTP
        </button>
    @else
        <h2 class="text-xl font-bold mb-5">
            Create New Password
        </h2>

        <div class="mb-4">
            <input type="password" wire:model="password" class="border rounded p-2 w-full" placeholder="Password">

            @error('password')
                <span class="text-red-500 text-sm">
                    {{ $message }}
                </span>
            @enderror
        </div>

        <div class="mb-4">
            <input type="password" wire:model="password_confirmation" class="border rounded p-2 w-full"
                placeholder="Confirm Password">
        </div>

        <button wire:click="savePassword" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
            Save Password
        </button>
    @endif

</div>
