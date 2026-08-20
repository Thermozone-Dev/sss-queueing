<div class="mx-auto w-full max-w-5xl space-y-8">

    <div class="flex items-center justify-between">

        <div>

            <h1 class="text-3xl font-bold tracking-tight text-gray-900">
                Select Time Slot
            </h1>

            <p class="mt-2 text-gray-500">
                Choose an available time for your appointment.
            </p>

        </div>

        <button type="button" wire:click="backToDate"
            class="rounded-xl border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            Change Date
        </button>

    </div>

    {{-- Appointment Summary --}}

    <div class="rounded-2xl border border-primary-100 bg-primary-50 p-5">

        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

            <div>

                <p class="text-xs font-medium uppercase tracking-wide text-primary-600">
                    Branch
                </p>

                <p class="mt-1 font-semibold text-gray-900">
                    {{ $selectedBranch->name }}
                </p>

            </div>

            <div>

                <p class="text-xs font-medium uppercase tracking-wide text-primary-600">
                    Transaction
                </p>

                <p class="mt-1 font-semibold text-gray-900">
                    {{ collect($transactions)->firstWhere('id', $selectedTransaction)['name'] ?? '' }}
                </p>

            </div>

            <div>

                <p class="text-xs font-medium uppercase tracking-wide text-primary-600">
                    Date
                </p>

                <p class="mt-1 font-semibold text-gray-900">
                    {{ \Carbon\Carbon::parse($selectedDate)->format('F d, Y') }}
                </p>

            </div>

        </div>

    </div>

    {{-- Business Hours --}}

    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

        <div class="flex items-center gap-3">

            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-primary-50">

                <x-heroicon-o-clock class="h-6 w-6 text-primary-600" />

            </div>

            <div>

                <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                    Business Hours
                </p>

                <p class="mt-1 font-semibold text-gray-900">

                    {{ \Carbon\Carbon::parse($selectedBranch->opening_hours)->format('h:i A') }}

                    –

                    {{ \Carbon\Carbon::parse($selectedBranch->closing_hours)->format('h:i A') }}

                </p>

            </div>

        </div>

        {{-- Time Slots --}}

        <div class="mt-6 grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5">

            @foreach ($timeSlots as $slot)
                @if ($slot['available'])
                    <button type="button" wire:click="selectTime('{{ $slot['value'] }}')"
                        class="
                            rounded-xl border px-4 py-3 text-sm font-medium transition
                            {{ $selectedTime === $slot['value']
                                ? 'border-primary-600 bg-primary-600 text-white'
                                : 'border-gray-200 bg-white text-gray-700 hover:border-primary-500 hover:bg-primary-50' }}
                        ">
                        {{ $slot['label'] }}
                    </button>
                @else
                    <button type="button" disabled
                        class="cursor-not-allowed rounded-xl border border-gray-100 bg-gray-50 px-4 py-3 text-sm font-medium text-gray-300">
                        {{ $slot['label'] }}
                    </button>
                @endif
            @endforeach

        </div>

        {{-- Confirm --}}

        @if ($selectedTime)
            <div class="mt-8 flex items-center justify-between border-t border-gray-100 pt-6">

                <div>

                    <p class="text-xs text-gray-400">
                        Selected Time
                    </p>

                    <p class="mt-1 font-semibold text-gray-900">
                        {{ \Carbon\Carbon::createFromFormat('H:i', $selectedTime)->format('h:i A') }}
                    </p>

                </div>

                <button type="button" wire:click="confirmAppointment" wire:loading.attr="disabled"
                    class="rounded-xl bg-primary-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-primary-700 disabled:opacity-50">
                    <span wire:loading.remove wire:target="confirmAppointment">
                        Confirm Appointment
                    </span>

                    <span wire:loading wire:target="confirmAppointment">
                        Sending...
                    </span>
                </button>

            </div>
        @endif

        {{-- Error --}}

        @error('appointment')
            <div class="mt-4 rounded-xl bg-danger-50 p-4 text-sm text-danger-700">
                {{ $message }}
            </div>
        @enderror

    </div>

</div>
