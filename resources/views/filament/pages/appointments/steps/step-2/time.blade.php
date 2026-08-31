<div class="flex min-h-[420px] h-full w-full flex-col gap-2 font-[Inter,sans-serif]">
    {{-- Legend --}}
    <div class="w-full rounded-lg border border-[#E5E7EB] p-4">
        <p class="mb-3  font-semibold text-[#1F2937]">Legend</p>
        <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text- text-[#6B7280] text-xs">
            <span class="flex items-center gap-2">
                <span class="h-4 w-4 rounded-xs border border-[#D1D5DB] bg-white"></span> Available
            </span>
            <span class="flex items-center gap-2">
                <span class="h-4 w-4 rounded-xs bg-[#1E50A1]"></span> <b class="font-semibold text-[#111827]">Selected</b>
            </span>
            <span class="flex items-center gap-2">
                <span class="h-4 w-4 rounded-xs border border-[#FECDD3] bg-[#FFF1F2]"></span> Holiday / Closed
            </span>
            <span class="flex items-center gap-2">
                <span class="flex h-4 w-4 items-center justify-center rounded- bg-[#F3F4F6]">🚫</span> Fully Booked
            </span>
            <span class="flex items-center gap-2">
                <span class="h-4 w-4 rounded-xs border border-dashed border-[#D1D5DB]"></span> Weekend - No operations
            </span>
        </div>
    </div>

    {{-- Time Slot Card --}}
    <div class="flex h-[320px] w-full flex-col rounded-lg border border-[#E5E7EB] p-5">
        {{-- Header --}}
        <div class="mb-4 flex shrink-0 items-start justify-between gap-3">
            <div>
                <h2 class="text- font-semibold text-[#111827]">Select Time Slot</h2>
                <p class="mt-0.5 text-xs text-[#6B7280]">
                    Available Slots for {{ $selectedDate? \Carbon\Carbon::parse($selectedDate)->format('M d') : '...' }}
                </p>
            </div>
            @if($timeSlots)
                <span class="shrink-0 text-xs font-normal text-[#6B7280]">
                    {{ collect($timeSlots)->where('available', true)->count() }} slots open
                </span>
            @endif
        </div>

        @if(empty($timeSlots))
            <div class="flex flex-1 items-center">
                <span class="w-full rounded-xl text-center p-4 text-sm text-[#6B7280]">
                    No time slots available for this date.
                </span>
            </div>
        @else
            <div class="h-full flow-y-auto pr-1 scrollbar-fade">
                <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                    @foreach($timeSlots as $slot)
                        @php
                            $value = $slot['value']?? '';
                            $isAvailable = (bool) ($slot['available']?? false);
                            $isSelected = $selectedTime === $value;
                            $remaining = $slot['remaining']?? null;
                            $isFull = ($remaining === 0) ||!$isAvailable;
                            
                        @endphp

                        @if($isAvailable)
                            <button
                                type="button"
                                wire:click="selectTime('{{ $value }}')"
                                class="relative flex min-h- flex-col justify-center rounded-xl border p-3 text-left transition
                                    {{ $isSelected
                                       ? 'border-[#1E50A1] bg-[#1E50A1] text-white'
                                        : 'border-[#E5E7EB] bg-white text-[#111827] hover:border-[#9CA3AF]' }}"
                            >
                                <div class="flex items-center justify-between">
                                    <span class="text- font-semibold">{{ $slot['label'] }}</span>
                                    @if($isSelected)
                                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    @endif
                                </div>
                                @if($remaining!== null)
                                    <span class="mt-1.5 inline-flex w-fit rounded- px-2 py-0.5 text- font-medium
                                        {{ $isSelected? 'bg-white/20 text-white' : ($remaining <= 1? 'bg-[#FEF3C7] text-[#92400E]' : 'bg-[#D1FAE5] text-[#065F46]') }}">
                                        {{ $remaining }} slot{{ $remaining > 1? 's' : '' }} left
                                    </span>
                                @endif
                            </button>
                       @else
                        @php
                            $isBreak = ($slot['reason'] ?? null) === 'BREAK';
                        @endphp

                        <div
                            class="flex min-h-[80px] flex-col justify-center rounded-xl border p-3 opacity-90
                                {{ $isBreak
                                    ? 'border-gray-300 bg-gray-200 cursor-not-allowed'
                                    : 'border-[#E5E7EB] bg-[#F3F4F6]'
                                }}"
                        >
                            <div
                                class="text-sm font-medium line-through
                                    {{ $isBreak ? 'text-gray-500' : 'text-[#9CA3AF]' }}"
                            >
                                {{ $slot['label'] }}
                            </div>

                            <span
                                class="mt-1.5 inline-flex w-fit rounded-md px-2 py-0.5 text-xs uppercase tracking-wide
                                    {{ $isBreak
                                        ? 'bg-amber-100 text-amber-700 '
                                        : 'bg-[#2F343F] text-white'
                                    }}"
                            >
                                {{ $isBreak ? 'BREAK' : 'FULL' }}
                            </span>
                        </div>
                    @endif

                    @endforeach
                </div>
            </div>
        @endif
    </div>

    {{-- Navigation --}}
    <div class="mt-auto flex items-center justify-between gap-3 pt-2">
        <button type="button" wire:click="backToBranch" class="rounded-lg border border-[#9CA3AF] px-7 py-2 text-xs font-medium text-[#111827] hover:bg-gray-50">
            Back
        </button>
        <div class="flex items-center gap-4">
            <p class="text-xs text-[#6B7280]">Step 2 of 3</p>
            <button type="button" wire:click="continueToConfirmation" @disabled(!$selectedTime) class="rounded-lg bg-[#1E50A1] px-8 py-2 text-xs font-semibold text-white disabled:bg-[#CBD5E1]">
                Continue
            </button>
        </div>
    </div>
</div>