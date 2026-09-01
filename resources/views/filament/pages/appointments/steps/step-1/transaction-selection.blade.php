            <div class="flex h-full flex-col justify-between space-y-6 p-3 font-['Inter',sans-serif] sm:p-6">
                {{-- Transaction Header --}}
                <div class="space-y-3">
                        <div class="space-y-2">

                            <h2 class="text-lg font-semibold text-gray-800 sm:text-xl">
                                TRANSACTION TYPES
                            </h2>

                            <p class="text-xs font-light text-gray-700 sm:text-sm">
                                Choose the transaction type you want to proceed with
                            </p>

                        </div>


                {{-- Transaction Dropdown --}}
                @php
                    $selectedTransactionData = collect($transactions)
                        ->firstWhere('id', $selectedTransaction);
                @endphp


                <div
                    x-data="{ open: false }"
                    class="relative w-full"
                >

                    {{-- Selected Transaction --}}
                    <button
                        type="button"
                        @click="open = !open"
                        @click.outside="open = false"
                        class="flex h-[48px] w-full items-center justify-between rounded-md
                               border border-gray-200 bg-white px-4 text-left text-sm
                               text-gray-700 transition
                               focus:border-[#1E50A1]
                               focus:outline-none"
                    >

                        <div class="flex min-w-0 items-center gap-3">

                            @if ($selectedTransactionData)

                                <span class="truncate">
                                    {{ $selectedTransactionData['name'] }}
                                </span>

                            @else

                                <span class="text-gray-500">
                                    Select transaction type
                                </span>

                            @endif

                        </div>


                        {{-- Chevron --}}
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="18"
                            height="18"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            class="shrink-0 text-gray-500 transition-transform duration-200"
                            :class="open ? 'rotate-180' : ''"
                        >
                            <path d="m6 9 6 6 6-6"/>
                        </svg>

                    </button>


                    {{-- Dropdown --}}
                    <div
                        x-show="open"
                        x-transition.opacity
                        class="absolute left-0 right-0 z-20 mt-2 overflow-hidden
                               rounded-md border border-gray-200 bg-white shadow-lg"
                    >

                        @forelse ($transactions as $transaction)

                            @php
                                $isTransactionSelected =
                                    $selectedTransaction === $transaction['id'];
                            @endphp


                            <button
                                wire:key="transaction-{{ $transaction['id'] }}"
                                type="button"
                                wire:click="selectTransaction('{{ $transaction['id'] }}')"
                                @click="open = false"
                                class="flex w-full items-center justify-between gap-3
                                       border-b border-gray-100 px-3 py-3 text-left
                                       transition hover:bg-[#F5F8FF]
                                       {{ $isTransactionSelected
                                            ? 'bg-[#F5F8FF]'
                                            : '' }}"
                            >

                                <div class="flex min-w-0 items-center gap-3">

                                    {{-- Details --}}
                                    <div class="min-w-0">

                                        <p class="truncate text-sm font-medium text-gray-800">
                                            {{ $transaction['name'] }}
                                        </p>

                                        <p class="truncate text-xs text-gray-500">
                                            {{ $transaction['description'] }}
                                        </p>

                                    </div>

                                </div>


                                {{-- Selected --}}
                                @if ($isTransactionSelected)

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="18"
                                        height="18"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="#1E50A1"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="shrink-0"
                                    >
                                        <path d="M5 12.5 9.5 17 19 7.5"/>
                                    </svg>

                                @endif

                            </button>

                        @empty

                            <div class="px-3 py-3 text-sm text-gray-500">
                                Select a branch to view available transaction types.
                            </div>

                        @endforelse

                    </div>

                </div>

                     {{-- Information --}}
                <div class="flex items-center gap-2 text-xs text-gray-600 sm:text-sm">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="18"
                        height="18"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        class="shrink-0 text-gray-600"
                    >
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M12 16v-4"/>
                        <path d="M12 8h.01"/>
                    </svg>

                    <p class="leading-snug">
                        Required • Select one option to continue
                    </p>

                </div>
        </div>

                {{-- Footer --}}
                <div class="mt-auto flex items-end justify-end pt-4">

                    <div class="flex items-center gap-3">

                        <p class="text-[10px] font-light text-gray-600 sm:text-xs">
                            Step 1 of 3
                        </p>

                        <button
                            type="button"
                            wire:click="continueToDateTime"
                            wire:loading.attr="disabled"
                            wire:target="continueToDateTime"
                            class="rounded-lg bg-[#1E50A1] px-4 py-2 text-[10px] font-medium
                                   text-white sm:px-6 sm:text-xs
                                   disabled:cursor-not-allowed
                                   disabled:bg-slate-300"
                            @disabled(!$selectedBranch || !$selectedTransaction)
                        >
                            <span wire:loading.remove wire:target="continueToDateTime">
                                Continue
                            </span>

                            <span wire:loading wire:target="continueToDateTime">
                                Processing...
                            </span>
                        </button>

                    </div>

                </div>

            </div>
