<x-filament-panels::page :heading="false">

    @if ($step === 1)
        @include('filament.pages.appointments.steps.step-1.main')

    @elseif ($step === 2)
        @include('filament.pages.appointments.steps.step-2.main')

    @elseif ($step === 3)
        @include('filament.pages.appointments.steps.step-3.main')
    @endif

    <!-- Success Modal -->
    <div 
        x-data="{ open: false }" 
        x-init="@this.on('appointmentConfirmed', () => { open = true })"
    >
        <div 
            x-show="open" 
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4"
        >
            <div class="w-full max-w-md rounded-lg bg-white p-4 text-center shadow-lg sm:p-6">
                
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-success-50 sm:h-16 sm:w-16">
                    <x-heroicon-o-check class="h-7 w-7 text-success-600 sm:h-8 sm:w-8" />
                </div>

                <h2 class="mt-4 text-lg font-bold text-gray-900 sm:text-xl">
                    Appointment Created
                </h2>

                <p class="mt-2 text-sm text-gray-600 sm:text-base">
                    Your appointment has been successfully created.
                </p>
                <button 
                    @click="open = false; $wire.clearBranchSelection(); $wire.set('step', 1)" 
                    class="mt-6 rounded-lg bg-primary-600 px-4 py-2 text-sm text-white hover:bg-primary-700 sm:text-base"
                >
                    Close
                </button>
            </div>
        </div>
    </div>

</x-filament-panels::page>

@vite('resources/js/app.js')
