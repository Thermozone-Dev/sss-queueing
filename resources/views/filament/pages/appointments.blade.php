<x-filament-panels::page :heading="false">

    @if ($step === 1)
        @include('filament.pages.appointments.steps.branch')
    @elseif ($step === 2)
        @include('filament.pages.appointments.steps.transaction')
    @elseif ($step === 3)
        @include('filament.pages.appointments.steps.date')
    @elseif ($step === 4)
        @include('filament.pages.appointments.steps.time')
    @elseif ($step === 5)
        @include('filament.pages.appointments.steps.confirmation')
    @endif

</x-filament-panels::page>
