
<div class="grid grid-cols-1 gap-6 mt-6 text-white items-center">
    @foreach ($transactions as $transactionItem)
        <div wire:click="gotoViewTransaction({{$transactionItem->id}})" class="cursor-pointer flex justify-between items-center justify-center px-8 py-5 rounded-xl" style="background-color: #007236">
            <h1 class="capitalize font-bold text-3xl">{{$transactionItem->name}}</h1>
            <x-fas-chevron-right class="h-4 w-4"></x-fas-chevron-right>
        </div>
    @endforeach
</div>
<div class="text-center justify-center mt-5">
    <p class="text-xl text-black mb-1 font-bold">Please bring all required documents</p>
    <p class="text-black flex items-center justify-center">
        <x-fas-circle-check class="w-4 h-4 mr-2"></x-fas-circle-check>
        Check requirements list posted on each department
    </p>
    <span class="my-4">
        <p class="text-gray-600 text-center py-5">For assistance, please approach our staff</p>
    </span>
</div>
