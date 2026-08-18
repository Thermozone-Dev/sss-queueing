<div class="mx-auto w-full max-w-5xl space-y-8">

    <div class="flex items-center justify-between">

        <div>

            <h1 class="text-3xl font-bold tracking-tight text-gray-900">
                Select Date
            </h1>

            <p class="mt-2 text-gray-500">
                Choose an available date for your appointment.
            </p>

        </div>

        <button type="button" wire:click="backToTransaction"
            class="rounded-xl border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            Change Transaction
        </button>

    </div>

    {{-- Branch / Transaction Summary --}}

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

        <div class="rounded-2xl border border-gray-200 bg-white p-5">

            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                Branch
            </p>

            <p class="mt-2 font-semibold text-gray-900">
                {{ $selectedBranch['name'] }}
            </p>

        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-5">

            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                Transaction
            </p>

            <p class="mt-2 font-semibold text-gray-900">
                {{ collect($transactions)->firstWhere('id', $selectedTransaction)['name'] ?? '' }}
            </p>

        </div>

    </div>

    {{-- Calendar --}}

    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

        <h2 class="text-lg font-semibold text-gray-900">
            Appointment Date
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            Select a date when the branch is operating.
        </p>

        <div class="mt-6 max-w-md">

            <input type="date" min="{{ now()->format('Y-m-d') }}" wire:change="selectDate($event.target.value)"
                class="block w-full rounded-xl border-gray-300 px-4 py-3 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500">

        </div>

        {{-- Operating Days --}}

        <div class="mt-5">

            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                Operating Days
            </p>

            <div class="mt-2 flex flex-wrap gap-2">

                @foreach ($selectedBranch['operating_days'] as $day)
                    <span class="rounded-full bg-success-50 px-3 py-1 text-xs font-medium text-success-700">
                        {{ $day }}
                    </span>
                @endforeach

            </div>

        </div>

        {{-- Selected Date --}}

        @if ($selectedDate)
            <div class="mt-6 rounded-xl bg-success-50 p-4">

                <div class="flex items-center gap-3">

                    <x-heroicon-o-check-circle class="h-5 w-5 text-success-600" />

                    <div>

                        <p class="text-sm font-semibold text-success-800">
                            {{ \Carbon\Carbon::parse($selectedDate)->format('l, F d, Y') }}
                        </p>

                        <p class="mt-1 text-xs text-success-700">
                            Date available.
                        </p>

                    </div>

                </div>

            </div>
        @endif

    </div>

</div>
