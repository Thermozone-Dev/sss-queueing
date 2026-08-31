<div class="w-full h-full rounded-lg border border-gray-200 py-6 px-12 font-[Inter,sans-serif]">
    @php
        $referenceDate = $calendarMonth
            ? \Carbon\Carbon::parse($calendarMonth . '-01')->startOfMonth()
            : now()->startOfMonth();

        $monthStart = $referenceDate->copy()->startOfMonth();
        $firstWeekday = $monthStart->dayOfWeek;
        $daysInMonth = $referenceDate->daysInMonth;

        $calendarGrid = collect();

        foreach (range(0, $firstWeekday - 1) as $i) {
            $calendarGrid->push(null);
        }

        foreach (range(1, $daysInMonth) as $day) {
            $calendarGrid->push($monthStart->copy()->day($day));
        }

        $remainingCells = (7 - ($calendarGrid->count() % 7)) % 7;

        foreach (range(1, $remainingCells) as $i) {
            $calendarGrid->push(null);
        }

      

    @endphp

    <div class="mb-5 flex items-center justify-between">
        <h1 class="text-[22px] font-semibold tracking-[-0.02em] text-[#111827]">
            {{ $referenceDate->translatedFormat('F Y') }}
        </h1>

        <div class="flex items-center gap-2">
            <button
                type="button"
                wire:click="previousMonth"
                class="flex h-9 w-9 items-center justify-center rounded-full border border-[#E7E8EB] bg-white text-[#8A8E97] transition hover:bg-[#F8F8F9]"
                aria-label="Previous month"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path d="M15 19l-7-7 7-7"/>
                </svg>
            </button>

            <button
                type="button"
                wire:click="nextMonth"
                class="flex h-9 w-9 items-center justify-center rounded-full border border-[#E7E8EB] bg-white text-[#8A8E97] transition hover:bg-[#F8F8F9]"
                aria-label="Next month"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>
    </div>

    <div class="mb-3 grid grid-cols-7 gap-2">
        @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
            <div class="flex h-8 items-center justify-center text-xs font-medium text-[#9DA0A8]">
                {{ $day }}
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-7 gap-2">
        @foreach ($calendarGrid as $date)
            @if (is_null($date))
                <div class="h-12 rounded-xl bg-transparent"></div>
            @else
                @php
                    $dateString = $date->format('Y-m-d');

                    $isPast = $date->lt(now()->startOfDay());

                    $isSelected = $selectedDate
                        && $dateString === $selectedDate;

                    $isOperatingDay = $selectedBranch
                        ? app(\App\Services\Appointment\ScheduleService::class)->isOperatingDay(
                            $dateString,
                            $selectedBranch
                        )
                        : false;

                    $isDisabledDate = $selectedBranch
                        ? app(\App\Services\Appointment\ScheduleService::class)->isDisabledDate(
                            $dateString,
                            $selectedBranch
                        )
                        : false;

                @endphp

                @if ($isPast)
                <div class="flex h-12 items-center justify-center rounded-xl bg-gray-100 text-sm font-medium text-gray-600 cursor-not-allowed">
                    {{ $date->day }}
                </div>

                @elseif ($isDisabledDate)
                    <div class="flex h-12 items-center justify-center rounded-xl border border-red-400 bg-red-100 text-sm font-medium text-gray-600 cursor-not-allowed">
                        {{ $date->day }}
                    </div>

                @elseif (!$isOperatingDay)
                    <div class="flex h-12 items-center justify-center rounded-xl border border-dashed border-gray-800 text-sm font-medium text-gray-600 cursor-not-allowed">
                        {{ $date->day }}
                    </div>

                @else
                    <button
                        type="button"
                        wire:click="selectDate('{{ $date->format('Y-m-d') }}')"
                        class="h-12 rounded-xl border text-sm font-medium transition {{ $isSelected
                            ? 'border-[#234EB9] bg-[#234EB9] text-white shadow-[0_1px_2px_rgba(35,78,185,0.25)]'
                            : 'border-[#E8EAED] bg-white text-[#1F232E] hover:border-[#D1D5DB]' }}"
                    >
                        {{ $date->day }}
                    </button>
                @endif
            @endif
        @endforeach
    </div>
</div>
