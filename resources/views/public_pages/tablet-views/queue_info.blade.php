
<div class="items-center justify-center">
    <div class="text-center mb-8 p-4 flex justify-center items-center rounded-xl bg-gray-200 shadow inset-shadow-lg inset-shadow-black">
        <x-fas-check-circle class="w-12 " style="color: #84CC16 "></x-fas-check-circle>
        <span class="pl-4 uppercase text-xl">queue number generated!</span>
    </div>
    <div class="text-center shadow rounded-2xl px-4 py-6" style="background-color: #84CC16">
        <p class="font-bold text-2xl">Your Queue Number:</p>
        <span class="text-white font-black" style="font-size: 6rem">{{$queue_details->getQueueNumber()}}</span>
        <p class="font-bold text-2xl captitalize">{{$selected_transaction->name}}</p>
    </div>
</div>
<div class="text-center my-5">
    <p class="text-xl text-black font-semibold">Please wait for your name or number to be called</p>
    <p class="text-gray-600 text-center">For assistance, please approach our staff</p>
</div>

<div class="flex justify-center my-4">
    <button wire:click="getPageDetails(1)" class="flex px-4 py-2 items-center text-white font-bold rounded-lg text-md" style="background-color: #00411F">
        Get Another Number
        <x-fas-chevron-right class="ml-2 w-4 h-4 m-0 p-0"></x-fas-chevron-right>
    </button>
</div>

