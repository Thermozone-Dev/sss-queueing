<div class="mx-auto w-full max-w-5xl space-y-8">

    <div class="flex items-center justify-between">

        <div>
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">
                Select Transaction
            </h1>

            <p class="mt-2 text-gray-500">
                Choose the type of transaction you want to process.
            </p>
        </div>

        <button type="button" wire:click="backToBranch"
            class="rounded-xl border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            Change Branch
        </button>

    </div>

    {{-- Selected Branch --}}

    <div class="rounded-2xl border border-primary-100 bg-primary-50 p-5">

        <div class="flex items-center gap-4">

            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white">

                <x-heroicon-o-building-office-2 class="h-6 w-6 text-primary-600" />

            </div>

            <div>

                <p class="text-xs font-medium uppercase tracking-wide text-primary-600">
                    Selected Branch
                </p>

                <p class="mt-1 font-semibold text-gray-900">
                    {{ $selectedBranch['name'] }}
                </p>

            </div>

        </div>

    </div>

    {{-- Transactions --}}

    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

        @foreach ($transactions as $transaction)
            <button type="button" wire:click="selectTransaction('{{ $transaction['id'] }}')"
                class="group rounded-2xl border border-gray-200 bg-white p-6 text-left shadow-sm transition hover:border-primary-500 hover:shadow-md">

                <div class="flex items-start gap-4">

                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-primary-50">

                        @if ($transaction['id'] === 'membership')
                            <x-heroicon-o-user class="h-6 w-6 text-primary-600" />
                        @elseif ($transaction['id'] === 'loans')
                            <x-heroicon-o-banknotes class="h-6 w-6 text-primary-600" />
                        @elseif ($transaction['id'] === 'benefits')
                            <x-heroicon-o-document-check class="h-6 w-6 text-primary-600" />
                        @else
                            <x-heroicon-o-credit-card class="h-6 w-6 text-primary-600" />
                        @endif

                    </div>

                    <div>

                        <h3 class="font-semibold text-gray-900 group-hover:text-primary-600">
                            {{ $transaction['name'] }}
                        </h3>

                        <p class="mt-1 text-sm leading-6 text-gray-500">
                            {{ $transaction['description'] }}
                        </p>

                    </div>

                </div>

            </button>
        @endforeach

    </div>

</div>
