<div class="flex flex-col justify-center items-center space-y-4 w-full max-w-sm mx-auto h-screen">

    <h2 class="text-2xl font-semibold">
        Welcome Back
    </h2>

    <p class="text-sm text-gray-500 text-center">
        Login to your SSS Online Services account.
    </p>

    <div class="space-y-4 w-full">

        {{-- EMAIL --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Email Address
            </label>

            <input type="email" wire:model="email" placeholder="Enter your email"
                class="border border-gray-300 p-3 rounded-lg w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

            @error('email')
                <p class="text-red-500 text-sm mt-1">
                    {{ $message }}
                </p>
            @enderror
        </div>


        {{-- PASSWORD --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Password
            </label>

            <input type="password" wire:model="password" placeholder="Enter your password"
                class="border border-gray-300 p-3 rounded-lg w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

            @error('password')
                <p class="text-red-500 text-sm mt-1">
                    {{ $message }}
                </p>
            @enderror
        </div>

    </div>


    <button wire:click="login" wire:loading.attr="disabled"
        class="w-full bg-blue-600 text-white px-4 py-3 rounded-lg text-sm font-semibold
               hover:bg-blue-700 transition disabled:opacity-50">

        <span wire:loading.remove wire:target="login">
            Login
        </span>

        <span wire:loading wire:target="login">
            Verifying...
        </span>

    </button>

</div>
