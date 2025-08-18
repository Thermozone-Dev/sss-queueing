
<div class="items-center justify-center bg-white bg-white shadow rounded-xl p-5 mb-3">
    <div class="pt-5">
        <h1 class="text-3xl font-extrabold text-black capitalize">{{$selected_transaction->name}}</h1>
        <p class="text-black-400 ">Please read the guidelines and ensure you have the required documents before proceeding.</p>
    </div>

    <div id="step-section" class="mt-3">
        <h4 class="text-black font-bold text-lg my-4">Steps</h4>
        <div class="bg-gray-200 h-48 max-h-48 overflow-auto rounded-xl p-4">
            @if (!empty($selected_transaction->transaction_steps))
                <ol>
                    @foreach ($selected_transaction->transaction_steps->sortBy('sort_order') as $key => $steps)
                        <li><span class="font-semibold mr-2">{{$key + 1}}. {{$steps->title}}</span> <span class="">{{'- '.$steps->description ?? null}}</span> </li>
                    @endforeach
                </ol>
            @endif
        </div>
    </div>

    <div id="requirements-section" class="mt-3 mb-4">
        <h4 class="text-black font-bold text-lg my-4">Requirements</h4>
        <div class="bg-gray-200 h-48 max-h-48 overflow-auto rounded-xl p-4">
            {!! $selected_transaction->required_documents !!}
        </div>
    </div>
    <div class="w-full flex justify-end my-2">
        <button wire:click="getPageDetails(4)" class="rounded-lg px-4 py-1 text-white self-end text-lg mr-2" style="background-color: green;">Proceed</button>
    </div>
</div>
