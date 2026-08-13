<div class="flex flex-col justify-center items-center space-y-4 w-full p-2 h-screen">

    @if ($existingAccount)
        {{-- EXISTING ACCOUNT --}}

        <h2 class="text-xl font-semibold">
            Login
        </h2>

        <p class="mb-4 text-center">
            Welcome back,
            <strong>
                {{ $member['first_name'] }}
                {{ $member['last_name'] }}
            </strong>
        </p>

        <div class="space-y-3 w-full">

            <div>
                <input type="email" value="{{ $email }}" disabled
                    class="border border-gray-300 bg-gray-100 p-2 rounded w-full">
            </div>

            <div>
                <input type="password" wire:model="password" placeholder="Enter your password"
                    class="border border-gray-300 p-2 rounded w-full">

                @error('password')
                    <p class="text-red-500 text-sm mt-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>

        </div>

        <button wire:click="loginExistingAccount" wire:loading.attr="disabled"
            class="bg-blue-500 text-white px-4 py-2 mt-3 text-sm rounded disabled:opacity-50 disabled:cursor-not-allowed">

            <span wire:loading.remove wire:target="loginExistingAccount">
                Login
            </span>

            <span wire:loading wire:target="loginExistingAccount">
                Logging in...
            </span>

        </button>
    @else
        {{-- NEW ACCOUNT --}}

        <h2 class="text-xl font-semibold">
            Create Password
        </h2>

        <p class="mb-4 text-center">
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
                    <p class="text-red-500 text-sm mt-1">
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
            class="bg-blue-500 text-white px-4 py-2 mt-3 text-sm rounded disabled:opacity-50 disabled:cursor-not-allowed">

            <span wire:loading.remove wire:target="savePassword">
                Create Account
            </span>

            <span wire:loading wire:target="savePassword">
                Creating Account...
            </span>

        </button>
    @endif

</div>
