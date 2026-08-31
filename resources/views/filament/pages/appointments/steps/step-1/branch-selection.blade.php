<div class="h-full xl:col-span-3 xl:row-span-5 font-['Inter',sans-serif] p-6 text-gray-800 space-y-2.5">
    <div class="pr-1.5 space-y-1">
        <div class="flex justify-between items-center ">
                <h5 class="text-sm font-medium">SSS Branches</h5>
                <p class="text-xs font-light">
                    {{ $this->filteredBranches->count() }}  branches found
                </p>
        </div>

            <div class="flex w-full gap-3 border border-gray-200 p-2 text-sm rounded-md" >
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
                    class="shrink-0 text-gray-400">
                <path d="m21 21-4.34-4.34"/>
                <circle cx="11" cy="11" r="8"/>
            </svg>
            <input wire:model.live.debounce.300ms="branchSearch"
                    class="w-full border-0 bg-transparent p-0 text-sm outline-none
                    placeholder:text-gray-400
                    focus:border-0 focus:outline-none focus:ring-0"
                    type="search" 
                    placeholder="Search branch, city or address" />
            </div>
    </div>
    

     <div class="h-[320px] flex-1 space-y-3 overflow-y-auto scrollbar-fade ">
          @forelse ($this->filteredBranches as $branch)
                  @php
                       $isSelected =
                       $selectedBranch?->id === $branch->id;
                  @endphp
          <button 
            wire:key="branch-{{ $branch->id }}"
             type="button"
             wire:click="selectBranch({{ $branch->id }})"
            class="w-full flex gap-6  py-2 px-4 justify-between items-center rounded-md border 
                     {{ $isSelected
                       ? 'border-[#1E50A1] bg-[#F5F8FF]'
                       : 'border-gray-200 hover:border-[#1E50A1]' }} 
                     {{$branch->is_active ? 'bg-transparent'
                       : 'bg-gray-100/40 cursor-not-allowed'}}"
          

              @disabled(!$branch->is_active)
            >
            <div class="flex gap-6 items-center w-full">
                <div class="p-2 rounded-full 
                     {{$isSelected ? 'bg-[#1E50A1] text-white'
                                   : 'bg-gray-100 ' }}">
                    <svg 
                        xmlns="http://www.w3.org/2000/svg" 
                        width="24" height="24" viewBox="0 0 24 24" 
                        fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" 
                        class="lucide lucide-map-pin-icon lucide-map-pin">
                        <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/>
                        <circle cx="12" cy="10" r="3"/></svg>
                </div>
                <div class="flex flex-col gap-3 py-1.5">
                    <div class="flex flex-col justify-start text-start capitalize">
                        <h3 class="text-sm font-medium ">
                        {{$branch->name}}
                        </h3>
                        <h4 class="text-xs font-light">
                            {{$branch->city}}, {{$branch->province}}
                        </h4>
                    </div>
                
                    <p class="text-xs font-light ">
                    @if ($branch->opening_hours && $branch->closing_hours)
                        ◷
                        {{ \Carbon\Carbon::parse($branch->opening_hours)->format('h:i A') }}
                        -
                        {{ \Carbon\Carbon::parse($branch->closing_hours)->format('h:i A') }}

                    @else
                    ◷ Schedule unavailable
                    @endif
                    </p>
                </div>
            </div>
          <div class="flex flex-col justify-between items-end self-stretch">
                    <p class="text-xs px-5 py-0.5 rounded-lg
                        {{ $branch->is_active
                            ? 'bg-green-100 border border-green-400 text-green-600'
                            : 'bg-gray-100 border border-gray-400 text-gray-500'
                        }}">
                        {{ $branch->is_active ? 'available' : 'Unavailable' }}
                    </p>

                    @if ($isSelected)
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            fill="#1E50A1"
                            stroke="white"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            class="shrink-0"
                        >
                            <circle cx="12" cy="12" r="10"/>
                            <path d="m16 9-5.5 5.5L8 12"/>
                        </svg>
                    @endif
                </div>
          </button>
            @empty
                    <div class="text-sm text-gray-500 p-4 text-center">
                        No branches found for "{{ $branchSearch }}"
                    </div>
         @endforelse
     </div>
</div>
