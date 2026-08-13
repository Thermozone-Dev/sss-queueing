@php
    $isAppointmentStep = $step == 4;
@endphp

<div
    class="{{ $isAppointmentStep
        ? 'w-screen h-screen bg-white'
        : 'min-h-screen flex flex-col md:grid md:grid-cols-[70%_30%] md:grid-rows-5' }}">

    @unless ($isAppointmentStep)
        <div class="hidden md:block row-span-5 relative overflow-hidden">
            <img src="{{ asset('images/cover.webp') }}" alt="SSS Appointment Cover"
                class="absolute inset-0 w-full h-full object-cover object-center" />

            {{-- Overlays --}}
            <div class="absolute inset-0 bg-black/40"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>

            {{-- Content --}}
            <div class="absolute inset-0 flex items-center justify-center p-8 lg:p-12">
                <div class="text-center text-white space-y-6 max-w-lg">

                    {{-- Badge --}}
                    {{-- <div
                        class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text- font-semibold tracking-widest">
                        <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                        NO WALK-IN • APPOINTMENT ONLY
                    </div> --}}

                    <div class="space-y-4">
                        <h1 class="text-4xl md:text- font-bold tracking-tight leading-[1.1]">
                            Skip the Line.<br>
                            <span class="text-blue-200">Book Your Appointment</span>
                        </h1>

                        <p class="text-sm md:text- leading-relaxed text-white/80 max-w-sm mx-auto">
                            Avoid long queues and save time. Schedule your visit to any SSS branch anytime, anywhere — fast
                            and hassle-free.
                        </p>
                    </div>

                    {{-- Steps --}}
                    <div class="grid grid-cols-3 gap-3 pt-6 text-left">
                        <div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl p-4">
                            <div
                                class="w-7 h-7 rounded-full bg-white text-black flex items-center justify-center text-xs font-bold mb-2">
                                1</div>
                            <p class="text-xs font-semibold">Choose Branch</p>
                            <p class="text- text-white/60 mt-1">Select nearest SSS</p>
                        </div>
                        <div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl p-4">
                            <div
                                class="w-7 h-7 rounded-full bg-white text-black flex items-center justify-center text-xs font-bold mb-2">
                                2</div>
                            <p class="text-xs font-semibold">Pick Date & Time</p>
                            <p class="text- text-white/60 mt-1">Your convenience</p>
                        </div>
                        <div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl p-4">
                            <div
                                class="w-7 h-7 rounded-full bg-blue-400 text-white flex items-center justify-center text-xs font-bold mb-2">
                                3</div>
                            <p class="text-xs font-semibold">Get Confirmed</p>
                            <p class="text- text-white/60 mt-1">Instant e-ticket</p>
                        </div>
                    </div>

                    <p class="flex items-center justify-center gap-2 text- text-white/50 pt-2">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Your information is secure and confidential
                    </p>

                </div>
            </div>
        </div>
    @endunless

    {{-- Content --}}
    <div class="{{ $isAppointmentStep ? 'w-full h-full' : 'w-full row-span-5 bg-white p-6 overflow-y-auto' }}">
        @switch($step)
            @case(1)
                @include('livewire.register.steps.sss-verification')
            @break

            @case(2)
                @include('livewire.register.steps.otp-verification')
            @break

            @case(3)
                @include('livewire.register.steps.create-password')
            @break

            @case(4)
                @include('livewire.register.steps.appointment')
            @break
        @endswitch
    </div>

</div>
