<div class="flex h-full min-h-[420px] w-full flex-col gap-2 font-[Inter,sans-serif]">

   <div class="w-full space-y-2 rounded-lg border border-gray-200 p-3 text-gray-700 sm:p-6">
      <h2 class="text-sm font-semibold sm:text-base">Booking Protection</h2>
      <div class="flex items-center gap-3 text-xs sm:text-sm">
        <svg xmlns="http://www.w3.org/2000/svg"
         width="20" height="20" viewBox="0 0 24 24"
          fill="none" stroke="currentColor" stroke-width="2" 
          stroke-linecap="round" stroke-linejoin="round" 
          class="lucide lucide-check-icon lucide-check shrink-0 text-green-500 sm:h-6 sm:w-6">
          <path d="M20 6 9 17l-5-5"/></svg>
          <p class="font-light">Slot re-checked — not full booked</p>
      </div>
      <div class="flex items-center gap-3 text-xs sm:text-sm">
        <svg xmlns="http://www.w3.org/2000/svg"
         width="20" height="20" viewBox="0 0 24 24"
          fill="none" stroke="currentColor" stroke-width="2" 
          stroke-linecap="round" stroke-linejoin="round" 
          class="lucide lucide-check-icon lucide-check shrink-0 text-green-500 sm:h-6 sm:w-6">
          <path d="M20 6 9 17l-5-5"/></svg>
          <p class="font-light">Branch open on selected date</p>
      </div>
      <div class="flex items-center gap-3 text-xs sm:text-sm">
        <svg xmlns="http://www.w3.org/2000/svg"
         width="20" height="20" viewBox="0 0 24 24"
          fill="none" stroke="currentColor" stroke-width="2" 
          stroke-linecap="round" stroke-linejoin="round" 
          class="lucide lucide-check-icon lucide-check shrink-0 text-green-500 sm:h-6 sm:w-6">
          <path d="M20 6 9 17l-5-5"/></svg>
          <p class="font-light">No overlapping appointment</p>
      </div>
      <div class="flex items-center gap-3 text-xs sm:text-sm">
        <svg xmlns="http://www.w3.org/2000/svg"
         width="20" height="20" viewBox="0 0 24 24"
          fill="none" stroke="currentColor" stroke-width="2" 
          stroke-linecap="round" stroke-linejoin="round" 
          class="lucide lucide-check-icon lucide-check shrink-0 text-green-500 sm:h-6 sm:w-6">
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


   <div class="rounded-lg border border-gray-200 p-3 sm:p-6">
    <div class="flex items-start gap-3">
        <input 
            type="checkbox" 
                    wire:model.live="agreedToPolicies" 
                    id="policies" 
                    class="mt-0.5 h-4 w-4 rounded border-gray-300 text-[#1E50A1] focus:ring-[#1E50A1] sm:h-5 sm:w-5"
                >
                <label for="policies" class="text-xs leading-5 text-gray-700 sm:text-sm">
                    I agree to <span class="font-medium">SSS appointment policies</span>, data privacy notice, 
                    and understand that fully booked or holiday slots will be automatically rejected. 
                    I will bring valid IDs.
                </label>
            </div>

            @error('policies')
                <p class="mt-2 text-xs text-red-600 sm:text-sm">{{ $message }}</p>
            @enderror
        </div>


     <div class="mt-auto flex items-center justify-between gap-2 pt-2 sm:gap-3">
        <button type="button" wire:click="backToDateTime" class="rounded-lg border border-[#9CA3AF] px-4 py-2 text-[10px] font-medium text-[#111827] hover:bg-gray-50 sm:px-7 sm:text-xs">
            Back
        </button>
        <div class="flex items-center gap-2 sm:gap-4">
            <p class="text-[10px] text-[#6B7280] sm:text-xs">Step 3 of 3</p>
            <button type="button" wire:click="confirmAppointment" @disabled(!$selectedTime || !$agreedToPolicies) class="rounded-lg bg-[#1E50A1] px-5 py-2 text-[10px] font-semibold text-white disabled:bg-[#CBD5E1] sm:px-8 sm:text-xs">
                Submit Appointment
            </button>
        </div>
    </div>
</div>