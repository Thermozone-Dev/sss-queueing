<x-filament-panels::page :heading="false">

    @if ($step === 1)
        @include('filament.pages.appointments.steps.step-1.main')

    @elseif ($step === 2)
        @include('filament.pages.appointments.steps.step-2.main')
    @elseif ($step === 3)
        @include('filament.pages.appointments.steps.step-3.main')
    @elseif ($step === 4)
        @include('filament.pages.appointments.steps.confirmation')
    @endif

</x-filament-panels::page>

@vite('resources/js/app.js')
