<div class="flex flex-col justify-center items-center space-y-4 w-full h-screen">

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
        class="bg-green-500 text-white px-4 py-2 mt-3 text-sm rounded disabled:opacity-50 disabled:cursor-not-allowed">
        <span wire:loading.remove wire:target="verifyOtp">
            Verify OTP
        </span>

        <span wire:loading wire:target="verifyOtp">
            Verifying...
        </span>
    </button>

</div>
