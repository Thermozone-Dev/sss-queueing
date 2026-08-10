<div class="{{ $step == 4 ? 'w-screen h-screen bg-white' : 'min-h-screen flex justify-end items-center bg-slate-50 ' }}">
    <div
        class="{{ $step == 4 ? 'w-full h-full' : 'w-full max-w-md min-h-[calc(100vh-2rem)] bg-white p-6 overflow-y-auto' }}">

        @if ($step == 1)
            @include('livewire.register.steps.sss-verification')
        @elseif ($step == 2)
            @include('livewire.register.steps.otp-verification')
        @elseif ($step == 3)
            @include('livewire.register.steps.create-password')
        @elseif ($step == 4)
            @include('livewire.register.steps.appointment')
        @endif

    </div>
</div>
