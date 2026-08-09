<div class="min-h-screen flex justify-end items-center bg-slate-50 p-4">
    <div class="w-full max-w-md h-screen bg-white p-6">
        @if ($step == 1)
            @include('livewire.register.steps.sss-verification')
        @endif

        @if ($step == 2)
            @include('livewire.register.steps.otp-verification')
        @endif

        @if ($step == 3)
            @include('livewire.register.steps.create-password')
        @endif

        @if ($step == 4)
            @include('livewire.register.steps.success')
        @endif
    </div>
</div>
