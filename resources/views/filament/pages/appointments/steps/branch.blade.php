<div class="space-y-8">

    <div>
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">
            Welcome to SSS Appointment
        </h1>

        <p class="mt-2 text-gray-500">
            Please select your preferred SSS branch to continue.
        </p>
    </div>

    <div>
        <h2 class="text-xl font-semibold text-gray-900">
            Select Branch
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            Choose a branch where you want to process your transaction.
        </p>
    </div>

    <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">

        @foreach ($branches as $branch)
            <div
                class="relative rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition hover:border-primary-500 hover:shadow-md">

                {{-- Status --}}
                <div class="absolute right-5 top-5">

                    @if ($branch['appointment_enabled'] && $branch['status'] === 'active')
                        <span class="rounded-full bg-success-50 px-3 py-1 text-xs font-medium text-success-700">
                            Available
                        </span>
                    @else
                        <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-500">
                            Unavailable
                        </span>
                    @endif

                </div>

                {{-- Icon --}}
                <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-primary-50">

                    <x-heroicon-o-building-office-2 class="h-6 w-6 text-primary-600" />

                </div>

                {{-- Name --}}
                <h3 class="pr-20 text-lg font-semibold text-gray-900">
                    {{ $branch['name'] }}
                </h3>

                {{-- Address --}}
                <div class="mt-3 flex gap-2 text-sm text-gray-500">

                    <x-heroicon-o-map-pin class="mt-0.5 h-5 w-5 shrink-0" />

                    <div>

                        <p>
                            {{ $branch['address']['line1'] }}
                        </p>

                        <p>
                            {{ $branch['address']['city'] }},
                            {{ $branch['address']['province'] }}
                            {{ $branch['address']['postal_code'] }}
                        </p>

                    </div>

                </div>

                {{-- Working Hours --}}
                <div class="mt-4 flex gap-2 text-sm text-gray-500">

                    <x-heroicon-o-clock class="mt-0.5 h-5 w-5 shrink-0" />

                    <div>

                        <p>
                            {{ \Carbon\Carbon::createFromFormat('H:i', $branch['working_hours']['start'])->format('h:i A') }}

                            –

                            {{ \Carbon\Carbon::createFromFormat('H:i', $branch['working_hours']['end'])->format('h:i A') }}
                        </p>

                        <p class="mt-1">
                            {{ implode(', ', $branch['operating_days']) }}
                        </p>

                    </div>

                </div>

                {{-- Button --}}
                <div class="mt-6">

                    @if ($branch['appointment_enabled'] && $branch['status'] === 'active')
                        <button type="button" wire:click="selectBranch('{{ $branch['branch_id'] }}')"
                            class="w-full rounded-xl bg-primary-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-primary-700">
                            Select Branch
                        </button>
                    @else
                        <button type="button" disabled
                            class="w-full cursor-not-allowed rounded-xl bg-gray-100 px-4 py-2.5 text-sm font-semibold text-gray-400">
                            Appointment Unavailable
                        </button>
                    @endif

                </div>

            </div>
        @endforeach

    </div>

</div>
