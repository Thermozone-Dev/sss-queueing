
<div class="grid grid-cols-2 sm:grid-cols-2 xs:grid-cols-1 gap-6 mt-6 text-white items-center" >
    @foreach ($stations as $station)
        <div wire:click="gotoSubmenu({{ $station->id }})" class="cursor-pointer sm:px-4 xs:px-2 px-4 rounded-lg text-center justify-center py-5" style="background-color: #84CC16">
            {{-- <x-fas-file class=" text-green-900 "></x-fas-file> --}}
            @svg($station->icon, 'h-24 w-full text-green-900 mb-4')
            <h1 class="uppercase font-bold text-xl">{{$station->name}}</h1>
            <h4 class="uppercase text-sm">{{$station->description}}</h4>
        </div>
    @endforeach
</div>
<div class="text-center justify-center mt-5">
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
