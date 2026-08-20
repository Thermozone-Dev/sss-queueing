<div class="flex flex-col justify-center items-center space-y-4 w-full h-screen">

    <h2 class="text-xl font-bold">
        OTP Verification
    </h2>

    <p>
        OTP sent to:
        <strong>{{ $email }}</strong>
    </p>

    {{-- OTP BOXES --}}
    <div x-data="{
        otp: ['', '', '', '', '', ''],
    
        moveNext(index, event) {
            let value = event.target.value;
    
            // Allow numbers only
            value = value.replace(/[^0-9]/g, '');
    
            this.otp[index] = value;
    
            // Update Livewire OTP
            $wire.set('otp', this.otp.join(''));
    
            // Move to next box
            if (value && index < 5) {
                this.$refs['otp' + (index + 1)].focus();
            }
        },
    
        moveBack(index, event) {
            if (
                event.key === 'Backspace' &&
                !this.otp[index] &&
                index > 0
            ) {
                this.$refs['otp' + (index - 1)].focus();
            }
        },
    
        pasteOtp(event) {
            event.preventDefault();
    
            let pasted = event.clipboardData
                .getData('text')
                .replace(/[^0-9]/g, '')
                .slice(0, 6);
    
            this.otp = pasted
                .padEnd(6, '')
                .split('');
    
            $wire.set('otp', pasted);
    
            this.$nextTick(() => {
                let nextIndex = Math.min(pasted.length, 5);
                this.$refs['otp' + nextIndex].focus();
            });
        }
    }" class="flex gap-2 justify-center">

        @for ($i = 0; $i < 6; $i++)
            <input type="text" inputmode="numeric" maxlength="1" x-ref="otp{{ $i }}"
                x-model="otp[{{ $i }}]" @input="moveNext({{ $i }}, $event)"
                @keydown="moveBack({{ $i }}, $event)" @paste="pasteOtp($event)"
                class="w-12 h-12 border border-gray-300 rounded-lg text-center text-xl font-semibold focus:outline-none focus:ring-2 focus:ring-green-500" />
        @endfor

    </div>

    @error('otp')
        <p class="text-red-500 text-sm">
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
