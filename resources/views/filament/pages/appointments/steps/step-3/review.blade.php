  <div class="h-full w-full rounded-lg border border-gray-200 font-[Inter,sans-serif] text-gray-800">

    @php
            $user = auth()->user();
            $selectedTransactionData = collect($transactions)->firstWhere('id', $selectedTransaction);
    @endphp

    <div class="flex items-center justify-between gap-2 rounded-t-lg border-b border-gray-200 bg-gray-50 px-3 py-3 sm:px-4">
        <h2 class="text-xs font-medium text-gray-800 sm:text-sm">
            Appointment Summary
        </h2>

        <div class="rounded-lg border border-green-400 bg-green-100 px-2 py-1 text-[10px] font-medium text-green-700 sm:px-4 sm:text-xs">
            Verified • Slot Available
        </div>
    </div>

    <dl class="h-full px-2 text-left sm:px-4">
        <div class="flex items-start gap-3 px-2 py-3 sm:gap-8 sm:py-4">
            <dt class="w-20 text-[11px] font-medium text-gray-500 sm:w-24 sm:text-sm">
                Branch
            </dt>

            <dd class="text-[11px] text-gray-800 sm:text-sm">
               {{ $selectedBranch?->name }}
            </dd>
        </div>

        <div class="flex items-start gap-3 px-2 py-3 sm:gap-8 sm:py-4">
            <dt class="w-20 text-[11px] font-medium text-gray-500 sm:w-24 sm:text-sm">
                Transactions 
            </dt>

            <dd class="text-[11px] text-gray-800 sm:text-sm">
               {{ $selectedTransactionData['name'] ?? '' }}
            </dd>
        </div>

         <div class="flex items-start gap-3 px-2 py-3 sm:gap-8 sm:py-4">
            <dt class="w-20 text-[11px] font-medium text-gray-500 sm:w-24 sm:text-sm">
                Date 
            </dt>

            <dd class="text-[11px] text-gray-800 sm:text-sm">
                {{ \Carbon\Carbon::parse($selectedDate)->format('F d, Y') }}
            </dd>
        </div>

         <div class="flex items-start gap-3 px-2 py-3 sm:gap-8 sm:py-4">
            <dt class="w-20 text-[11px] font-medium text-gray-500 sm:w-24 sm:text-sm">
                Time
            </dt>

            <dd class="text-[11px] text-gray-800 sm:text-sm">
                {{ \Carbon\Carbon::createFromFormat('H:i', $selectedTime)->format('h:i A') }}
                @if ($selectedBranch?->opening_hours && $selectedBranch?->closing_hours)
                    ({{ \Carbon\Carbon::parse($selectedBranch->opening_hours)->format('gA') }}-{{ \Carbon\Carbon::parse($selectedBranch->closing_hours)->format('gA') }} business hours)
                @else
                    (business hours unavailable)
                @endif
            </dd>
        </div>
        <hr class="text-gray-300" />

         <div class="flex items-start gap-3 px-2 py-3 sm:gap-8 sm:py-4">
            <dt class="w-20 text-[11px] font-medium text-gray-500 sm:w-24 sm:text-sm">
                Member 
            </dt>

            <dd class="text-[11px] text-gray-800 sm:text-sm">
               {{ $user?->firstname }} {{ $user?->lastname }}
            </dd>
        </div>
         <div class="flex items-start gap-3 px-2 py-3 sm:gap-8 sm:py-4">
            <dt class="w-20 text-[11px] font-medium text-gray-500 sm:w-24 sm:text-sm">
                SSS Number
            </dt>

            <dd class="text-[11px] text-gray-800 sm:text-sm">
              {{ $user?->username }}
            </dd>
        </div>
         <div class="flex items-start gap-3 px-2 py-3 sm:gap-8 sm:py-4">
            <dt class="w-20 text-[11px] font-medium text-gray-500 sm:w-24 sm:text-sm">
                Contact 
            </dt>

            <dd class="text-[11px] text-gray-800 sm:text-sm">
                {{ $user?->email }}
            </dd>
        </div>
    </dl>

</div>
