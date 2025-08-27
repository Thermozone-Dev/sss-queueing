
<div class=" max-h-full">
    <div class="grid grid-cols-2 sm:grid-cols-2 xs:grid-cols-1 gap-6 text-white">
        @foreach ($stations as $station)
            <div wire:click="gotoSubmenu({{ $station->id }})" class="cursor-pointer sm:px-4 xs:px-2 px-4 rounded-lg text-center py-5   place-content-center" style="background-color: #84CC16">
                {{-- <x-fas-file class=" text-green-900 "></x-fas-file> --}}
                <div class="row-span-2 place-content-center">
                    @svg($station->icon, 'max-h-full text-green-900')
                </div>
                <div class="pt-1 row-span-1">
                    <h1 class="uppercase font-black font-bold text-xl">{{$station->name}}</h1>
                    <h4 class="uppercase text-md">{{$station->description}}</h4>
                </div>
            </div>
        @endforeach
    </div>
    <div class="text-center">
        <p class="text-xl text-black mb-1">Touch any button above to begin your transaction</p>
        <p class="text-gray-600">For assistance, please approach our staff</p>
        <span class="my-4 flex">
            <span class="mx-auto bg-gray-300 flex items-center rounded-lg px-5 py-1">
                <x-far-clock class="text-black-400 h-4 w-4 mr-5"></x-fas-clock>
                    <span class="font-bold">Operating Hours: </span>
                    8:00 AM - 5:00 PM Monday - Friday
            </span>
        </span>
    </div>
</div>
