<div class="space-y-2">
    <div class="">
        <h1 class="text-2xl font-semibold text-gray-800">
            Choose Appointment Date
        </h1>

        <h5 class="mt-1 font-light text-gray-600">
            Business days only. Sundays, holidays, and fully booked dates are disabled.
        </h5>
    </div>

    <div class="grid h-[480px] grid-cols-1 gap-6 xl:grid-cols-5 xl:grid-rows-5">
        <div class="h-full xl:col-span-3 xl:row-span-5">
            @include('filament.pages.appointments.steps.step-2.date')
        </div>

        <div class="h-full xl:col-span-2 xl:col-start-4 xl:row-span-5">
            @include('filament.pages.appointments.steps.step-2.time')
        </div>
    </div>
</div>