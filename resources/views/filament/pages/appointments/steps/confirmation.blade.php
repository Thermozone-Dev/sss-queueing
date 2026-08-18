<div class="mx-auto flex min-h-[500px] max-w-2xl items-center justify-center">

    <div class="w-full rounded-2xl border border-gray-200 bg-white p-10 text-center shadow-sm">

        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-success-50">

            <x-heroicon-o-check class="h-8 w-8 text-success-600" />

        </div>

        <h1 class="mt-6 text-2xl font-bold text-gray-900">
            Appointment Created
        </h1>

        <p class="mt-2 text-gray-500">
            Your appointment has been successfully created.
        </p>

        <div class="mt-6 rounded-xl bg-gray-50 p-5 text-left">

            <div class="space-y-3 text-sm">

                <div class="flex justify-between gap-4">

                    <span class="text-gray-500">
                        Branch
                    </span>

                    <span class="font-medium text-gray-900">
                        {{ $selectedBranch['name'] }}
                    </span>

                </div>

                <div class="flex justify-between gap-4">

                    <span class="text-gray-500">
                        Transaction
                    </span>

                    <span class="font-medium text-gray-900">
                        {{ collect($transactions)->firstWhere('id', $selectedTransaction)['name'] ?? '' }}
                    </span>

                </div>

                <div class="flex justify-between gap-4">

                    <span class="text-gray-500">
                        Date
                    </span>

                    <span class="font-medium text-gray-900">
                        {{ \Carbon\Carbon::parse($selectedDate)->format('F d, Y') }}
                    </span>

                </div>

                <div class="flex justify-between gap-4">

                    <span class="text-gray-500">
                        Time
                    </span>

                    <span class="font-medium text-gray-900">
                        {{ \Carbon\Carbon::createFromFormat('H:i', $selectedTime)->format('h:i A') }}
                    </span>

                </div>

            </div>

        </div>

        <div class="mt-6 rounded-xl bg-primary-50 p-4 text-sm text-primary-700">

            An email confirmation will be sent to your registered email address.

        </div>

    </div>

</div>
