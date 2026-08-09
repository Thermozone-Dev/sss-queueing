<div class="flex flex-col justify-center items-center space-y-4 w-full h-screen">


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
