<div class="space-y-2">
    <div class="px-6">
        <h1 class="text-2xl font-semibold text-gray-800">
            Select Branch and Transaction
        </h1>

        <h5 class="mt-1 font-light text-gray-700">
            Choose your preferred SSS branch and the services you need to transact.
        </h5>
    </div>

    <div class="grid h-[480px] grid-cols-1 gap-6 xl:grid-cols-5 xl:grid-rows-5">
        <div class="h-full xl:col-span-3 xl:row-span-5">
            @include('filament.pages.appointments.steps.step-1.branch-selection')
        </div>

        <div class="h-full xl:col-span-2 xl:col-start-4 xl:row-span-5">
            @include('filament.pages.appointments.steps.step-1.transaction-selection')
        </div>
    </div>
</div>