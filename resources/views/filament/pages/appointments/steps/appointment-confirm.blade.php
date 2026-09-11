<div class="flex w-full flex-col items-center justify-center px-4 py-6 sm:px-6 sm:py-8 md:p-12">
    @php
            $user = auth()->user();
            $selectedTransactionData = collect($transactions)->firstWhere('id', $selectedTransaction);
    @endphp

    <!-- Success Header -->
    <div class="w-full text-center space-y-2">

        <div class="flex flex-col items-center justify-center space-y-3">

            <!-- Check Icon -->
            <div class="rounded-full border border-green-600 bg-green-100 p-2">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="36"
                    height="36"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    class="text-green-600 sm:h-[42px] sm:w-[42px]"
                >
                    <path d="M20 6 9 17l-5-5"/>
                </svg>
            </div>

            <!-- Title -->
            <div class="space-y-1 px-2">
                <h1 class="text-lg font-semibold text-gray-800 sm:text-2xl">
                    Appointment Confirmed!
                </h1>

                <p class="mx-auto max-w-xl text-xs leading-5 text-gray-600 sm:text-sm sm:leading-5">
                    Your SSS appointment has been successfully booked.
                    Confirmation email sent.
                </p>
            </div>

        </div>
    </div>


    <!-- Appointment Card -->
    <div class="mt-6 w-full md:w-1/2 rounded-lg border border-gray-400 p-4 sm:mt-8 sm:p-6">

        <dl class="flex flex-col gap-5">

            <!-- Header -->
            <div>
                <h3 class="text-sm font-semibold text-gray-800 sm:text-base">
                    Appointment
                </h3>
            </div>


            <!-- Branch / Date & Time -->
            <div class="grid grid-cols-1 gap-4 text-xs font-medium sm:gap-5 md:grid-cols-2 md:gap-6 sm:text-sm">

                <div class="flex min-w-0 flex-col items-start space-y-1">
                    <dt class="text-[10px] uppercase text-gray-500 sm:text-xs">
                        Branch
                    </dt>

                    <dd class="break-words text-gray-800">
                      {{ $selectedBranch?->name }}
                    </dd>
                </div>

                <div class="flex min-w-0 flex-col items-start space-y-1">
                    <dt class="text-[10px] uppercase text-gray-500 sm:text-xs">
                        Date & Time
                    </dt>

                    <dd class="break-words text-gray-800">
                        {{ \Carbon\Carbon::parse($selectedDate)->format('F d, Y') }} • {{ \Carbon\Carbon::createFromFormat('H:i', $selectedTime)->format('h:i A') }}
                    </dd>
                </div>

            </div>


            <!-- Member / Transaction -->
            <div class="grid grid-cols-1 gap-4 text-xs font-medium sm:gap-5 md:grid-cols-2 md:gap-6 sm:text-sm">

                <div class="flex min-w-0 flex-col items-start space-y-1">
                    <dt class="text-[10px] uppercase text-gray-500 sm:text-xs">
                        Member
                    </dt>

                    <dd class="break-words text-gray-800">
                        {{ $user?->firstname }} {{ $user?->lastname }}
                    </dd>
                </div>

                <div class="flex min-w-0 flex-col items-start space-y-1">
                    <dt class="text-[10px] uppercase text-gray-500 sm:text-xs">
                        Transaction
                    </dt>

                    <dd class="break-words text-gray-800">
                      {{ $selectedTransactionData['name'] ?? '' }}
                    </dd>
                </div>

            </div>

        </dl>


        <!-- Email Notification -->
        <div class="mt-5 flex items-start gap-2 rounded-md border border-blue-600 bg-blue-50 px-3 py-3 text-start text-xs text-gray-600 sm:gap-3 sm:px-6 sm:text-sm">

            <svg
                xmlns="http://www.w3.org/2000/svg"
                width="22"
                height="22"
                viewBox="0 0 24 24"
                fill="none"
                stroke="#2563EB"
                stroke-width="1.3"
                stroke-linecap="round"
                stroke-linejoin="round"
                class="mt-0.5 shrink-0 sm:h-6 sm:w-6"
            >
                <path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7"/>
                <rect x="2" y="4" width="20" height="16" rx="2"/>
            </svg>

            <p class="min-w-0 leading-5">
                Confirmation email sent to your registered email and
                <span class="font-medium text-gray-700 break-all">
                    member_relations@sss.gov.ph
                </span>
                copy.
            </p>

        </div>

    </div>


    <!-- Action Buttons -->
    <div class="mt-5 flex w-full flex-col gap-2 text-xs font-semibold sm:mt-6 sm:w-auto sm:flex-row sm:items-center sm:justify-center sm:gap-3 sm:text-sm">

        <!-- Download -->
        <button
            type="button"
            wire:click="downloadAppointmentSlip"
            wire:loading.attr="disabled"
            wire:target="downloadAppointmentSlip"
            class="flex w-full items-center justify-center gap-2 rounded-2xl bg-blue-600 px-4 py-2.5 text-white disabled:cursor-not-allowed disabled:opacity-70 sm:w-auto"
        >
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
                class="sm:h-5 sm:w-5"
            >
                <path d="M12 15V3"/>
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                <path d="m7 10 5 5 5-5"/>
            </svg>

            <span wire:loading.remove wire:target="downloadAppointmentSlip">
                Download Appointment Slip
            </span>

            <span wire:loading wire:target="downloadAppointmentSlip">
                Generating PDF...
            </span>
        </button>

        <!-- Rebooking -->
        <button
            type="button"
            wire:click="rebookAppointment"
            class="flex w-full items-center justify-center gap-2 rounded-2xl border border-gray-400 px-5 py-2.5 text-gray-800 sm:w-auto sm:px-6"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                width="18"
                height="18"
                viewBox="0 0 24 24"
                fill="none"
                stroke="oklch(27.8% 0.033 256.848)"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                class="sm:h-5 sm:w-5"
            >
                <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
                <path d="M3 3v5h5"/>
            </svg>

            <span>Rebooking</span>
        </button>


        <!-- Done -->
        <button
            type="button"
            wire:click="goToStepOne"
            class="flex w-full items-center justify-center rounded-2xl border border-gray-400 px-5 py-2.5 text-gray-800 sm:w-auto sm:px-6"
        >
            Done
        </button>

    </div>

</div>