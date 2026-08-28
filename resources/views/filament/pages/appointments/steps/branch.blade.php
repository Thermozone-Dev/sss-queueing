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

        @forelse ($branches as $branch)

            <div
                class="relative rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition hover:border-primary-500 hover:shadow-md">

                {{-- Status --}}
                <div class="absolute right-5 top-5">

                    @if ($branch->is_active)
                        <span
                            class="rounded-full bg-success-50 px-3 py-1 text-xs font-medium text-success-700">
                            Available
                        </span>
                    @else
                        <span
                            class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-500">
                            Unavailable
                        </span>
                    @endif

                </div>

                {{-- Icon --}}
                <div
                    class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-primary-50">

                    <x-heroicon-o-building-office-2
                        class="h-6 w-6 text-primary-600"
                    />

                </div>

                {{-- Name --}}
                <h3 class="pr-20 text-lg font-semibold text-gray-900">
                    {{ $branch->name }}
                </h3>

                {{-- Code --}}
                @if ($branch->code)
                    <p class="mt-1 text-xs font-medium text-gray-400">
                        {{ $branch->code }}
                    </p>
                @endif

                {{-- Address --}}
                <div class="mt-3 flex gap-2 text-sm text-gray-500">

                    <x-heroicon-o-map-pin
                        class="mt-0.5 h-5 w-5 shrink-0"
                    />

                    <div>

                        @if ($branch->address_line_1)
                            <p>
                                {{ $branch->address_line_1 }}
                            </p>
                        @endif

                        @if ($branch->address_line_2)
                            <p>
                                {{ $branch->address_line_2 }}
                            </p>
                        @endif

                        <p>
                            {{ $branch->city }},
                            {{ $branch->province }}
                            {{ $branch->postal_code }}
                        </p>

                    </div>

                </div>

                {{-- Working Hours --}}
                <div class="mt-4 flex gap-2 text-sm text-gray-500">

                    <x-heroicon-o-clock
                        class="mt-0.5 h-5 w-5 shrink-0"
                    />

                    <div>

                        @if ($branch->opening_hours && $branch->closing_hours)

                            <p>
                                {{ \Carbon\Carbon::parse($branch->opening_hours)->format('h:i A') }}
                                –
                                {{ \Carbon\Carbon::parse($branch->closing_hours)->format('h:i A') }}
                            </p>

                        @else

                            <p>
                                Schedule unavailable
                            </p>

                        @endif

                        @if ($branch->businessDay)

                            <p class="mt-1">
                                {{ $branch->businessDay->operating_days }}
                            </p>

                        @else

                            <p class="mt-1 text-gray-400">
                                Operating days not configured
                            </p>

                        @endif

                    </div>

                </div>

                {{-- Button --}}
                <div class="mt-6">

                    @if ($branch->is_active)

                        <button
                            type="button"
                            wire:click="selectBranch({{ $branch->id }})"
                            class="w-full rounded-xl bg-primary-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-primary-700"
                        >
                            Select Branch
                        </button>

                    @else

                        <button
                            type="button"
                            disabled
                            class="w-full cursor-not-allowed rounded-xl bg-gray-100 px-4 py-2.5 text-sm font-semibold text-gray-400"
                        >
                            Appointment Unavailable
                        </button>

                    @endif

                </div>

            </div>

        @empty

            <div class="col-span-full rounded-xl border border-gray-200 bg-white p-8 text-center">

                <x-heroicon-o-building-office-2
                    class="mx-auto h-10 w-10 text-gray-400"
                />

                <h3 class="mt-3 text-lg font-semibold text-gray-900">
                    No branches available
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    There are currently no branches available for appointments.
                </p>

            </div>

        @endforelse

    </div>

</div>