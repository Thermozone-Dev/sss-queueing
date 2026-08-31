  <div class="w-full h-full rounded-lg border border-gray-200 font-[Inter,sans-serif] text-gray-800">

    @php
            $user = auth()->user();
            $selectedTransactionData = collect($transactions)->firstWhere('id', $selectedTransaction);
    @endphp

    <div class="flex items-center justify-between border-b border-gray-200 bg-gray-50 px-4 py-3">
        <h2 class="font-medium text-gray-800">
            Appointment Summary
        </h2>

        <div class="rounded-lg border border-green-400 bg-green-100 px-4 py-1 text-sm font-medium text-green-700">
            Verified • Slot Available
        </div>
    </div>

    <dl class="px-4 text-left h-full">
        <div class="flex items-center gap-8 py-4 px-2">
            <dt class="w-24 text-sm font-medium text-gray-500">
                Branch
            </dt>

            <dd class="text-sm text-gray-800 ">
               {{ $selectedBranch?->name }}
            </dd>
        </div>

        <div class="flex items-center gap-8 py-4 px-2">
            <dt class="w-24 text-sm font-medium text-gray-500">
                Transactions 
            </dt>

            <dd class="text-sm text-gray-800">
               {{ $selectedTransactionData['name'] ?? '' }}
            </dd>
        </div>

         <div class="flex items-center gap-8 py-4 px-2">
            <dt class="w-24 text-sm font-medium text-gray-500">
                Date 
            </dt>

            <dd class="text-sm text-gray-800">
                {{ \Carbon\Carbon::parse($selectedDate)->format('F d, Y') }}
            </dd>
        </div>

         <div class="flex items-center gap-8 py-4 px-2">
            <dt class="w-24 text-sm font-medium text-gray-500">
                Time
            </dt>

            <dd class="text-sm text-gray-800">
                {{ \Carbon\Carbon::createFromFormat('H:i', $selectedTime)->format('h:i A') }}
                @if ($selectedBranch?->opening_hours && $selectedBranch?->closing_hours)
                    ({{ \Carbon\Carbon::parse($selectedBranch->opening_hours)->format('gA') }}-{{ \Carbon\Carbon::parse($selectedBranch->closing_hours)->format('gA') }} business hours)
                @else
                    (business hours unavailable)
                @endif
            </dd>
        </div>
        <hr class="text-gray-300" />

         <div class="flex items-center gap-8 py-4 px-2">
            <dt class="w-24 text-sm font-medium text-gray-500">
                Member 
            </dt>

            <dd class="text-sm text-gray-800">
               {{ $user?->firstname }} {{ $user?->lastname }}
            </dd>
        </div>
         <div class="flex items-center gap-8 py-4 px-2">
            <dt class="w-24 text-sm font-medium text-gray-500">
                SSS Number
            </dt>

            <dd class="text-sm text-gray-800">
              {{ $user?->username }}
            </dd>
        </div>
         <div class="flex items-center gap-8 py-4 px-2">
            <dt class="w-24 text-sm font-medium text-gray-500">
                Contact 
            </dt>

            <dd class="text-sm text-gray-800">
                {{ $user?->email }}
            </dd>
        </div>
    </dl>

</div>
