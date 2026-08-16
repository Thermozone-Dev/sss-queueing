<div class="w-full h-full flex items-start justify-center overflow-hidden pt-8 sm:items-center sm:pt-0">
    <div class="relative w-full max-w-sm h-screen flex items-center px-4 sm:px-0">

        {{-- SSS FORM --}}
        <div wire:loading.remove wire:target="verify" class="w-full space-y-4">
            {{-- Header --}}
            <div class="space-y-1">
                <h1 class="text-xl font-bold leading-tight text-slate-900">
                    Welcome to SSS Online Services
                </h1>

                <p class="text-xs text-slate-500">
                    Enter your SSS number to continue.
                </p>
            </div>

            {{-- SSS Number --}}
            <div class="space-y-1.5">
                <label for="sssNumber" class="block text-xs font-medium text-slate-600">
                    SSS number
                </label>

                <input id="sssNumber" type="text" wire:model.blur="sssNumber" placeholder="12-3456789-0"
                    autocomplete="off" inputmode="numeric"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-center text-sm
                           text-slate-900 placeholder:text-slate-400
                           outline-none transition
                           focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20" />

                @error('sssNumber')
                    <p class="text-xs text-red-500">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Verify Button --}}
            <button type="button" wire:click="verify" wire:loading.attr="disabled" wire:target="verify"
                class="w-full rounded-lg bg-blue-600 px-6 py-2.5 text-xs font-medium text-white
                       transition hover:bg-blue-700
                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2
                       disabled:cursor-not-allowed disabled:opacity-60">
                Verify SSS
            </button>

            {{-- Security Notice --}}
            <div class="flex items-center justify-center gap-1.5 py-2 text-xs text-slate-500">
                <x-heroicon-o-shield-check class="h-4 w-4 shrink-0" />

                <span>
                    Your information is protected and securely processed.
                </span>
            </div>
        </div>


        {{-- LOADING --}}
        <div wire:loading.flex wire:target="verify"
            class="min-h-[220px] w-full flex-col items-center justify-center gap-4">
            {{-- Spinner --}}
            <svg class="h-9 w-9 animate-spin text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24" aria-hidden="true">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                    stroke-width="4" />

                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
            </svg>

            <div class="text-center">
                <h2 class="text-sm font-semibold text-slate-800">
                    Verifying your SSS...
                </h2>

                <p class="mt-1 text-xs text-slate-500">
                    Please wait a moment
                </p>
            </div>
        </div>

    </div>
</div>
