<div class="flex min-h-[420px] h-full w-full flex-col gap-2 font-[Inter,sans-serif]">

   <div class="w-full rounded-lg border border-gray-200 text-gray-700 p-6 space-y-2">
      <h2 class="font-semibold">Booking Protection</h2>
      <div class="flex items-center gap-3 text-sm ">
        <svg xmlns="http://www.w3.org/2000/svg"
         width="24" height="24" viewBox="0 0 24 24"
          fill="none" stroke="currentColor" stroke-width="2" 
          stroke-linecap="round" stroke-linejoin="round" 
          class="lucide lucide-check-icon lucide-check text-green-500">
          <path d="M20 6 9 17l-5-5"/></svg>
          <p class="font-light">Slot re-checked — not full booked</p>
      </div>
      <div class="flex items-center gap-3 text-sm ">
        <svg xmlns="http://www.w3.org/2000/svg"
         width="24" height="24" viewBox="0 0 24 24"
          fill="none" stroke="currentColor" stroke-width="2" 
          stroke-linecap="round" stroke-linejoin="round" 
          class="lucide lucide-check-icon lucide-check text-green-500">
          <path d="M20 6 9 17l-5-5"/></svg>
          <p class="font-light">Branch open on selected date</p>
      </div>
      <div class="flex items-center gap-3 text-sm ">
        <svg xmlns="http://www.w3.org/2000/svg"
         width="24" height="24" viewBox="0 0 24 24"
          fill="none" stroke="currentColor" stroke-width="2" 
          stroke-linecap="round" stroke-linejoin="round" 
          class="lucide lucide-check-icon lucide-check text-green-500">
          <path d="M20 6 9 17l-5-5"/></svg>
          <p class="font-light">No overlapping appointment</p>
      </div>
      <div class="flex items-center gap-3 text-sm ">
        <svg xmlns="http://www.w3.org/2000/svg"
         width="24" height="24" viewBox="0 0 24 24"
          fill="none" stroke="currentColor" stroke-width="2" 
          stroke-linecap="round" stroke-linejoin="round" 
          class="lucide lucide-check-icon lucide-check text-green-500">
          <path d="M20 6 9 17l-5-5"/></svg>
          <p class="font-light">Within 
              @if ($selectedBranch?->opening_hours && $selectedBranch?->closing_hours)
                    {{ \Carbon\Carbon::parse($selectedBranch->opening_hours)->format('gA') }}-{{ \Carbon\Carbon::parse($selectedBranch->closing_hours)->format('gA') }}
                @else
                    (business hours unavailable)
                @endif
            business hours</p>
      </div>
    </div>


    <div class="rounded-lg border border-gray-200  p-6">
        <label class="mt-7 flex items-start gap-3 text-sm text-gray-700">
            <input type="checkbox" wire:model.live="agreedToPolicies" class="mt-0.5 rounded border-gray-300 text-[#1E50A1] focus:ring-[#1E50A1]">
            <span>I agree to SSS appointment policies, data privacy notice, and 
                    understand that fully booked or holiday slots will be 
                    automatically rejected. I will bring valid IDs.</span>
        </label>
        @error('policies')
            <p class="mt-2 text-sm text-danger-600">{{ $message }}</p>
        @enderror
    </div>

     <div class="mt-auto flex items-center justify-between gap-3 pt-2">
        <button type="button" wire:click="backToDateTime" class="rounded-lg border border-[#9CA3AF] px-7 py-2 text-xs font-medium text-[#111827] hover:bg-gray-50">
            Back
        </button>
        <div class="flex items-center gap-4">
            <p class="text-xs text-[#6B7280]">Step 3 of 3</p>
            <button type="button" wire:click="confirmAppointment" @disabled(!$selectedTime || !$agreedToPolicies) class="rounded-lg bg-[#1E50A1] px-8 py-2 text-xs font-semibold text-white disabled:bg-[#CBD5E1]">
                Submit Appointment
            </button>
        </div>
    </div>
</div>