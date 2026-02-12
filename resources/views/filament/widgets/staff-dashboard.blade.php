<x-filament::widget>
    <x-filament::tabs label="Stations">

        @foreach ($assigned_station as $ass_station )
            <x-filament::tabs.item
                :active="$station->id === $ass_station->id"
                wire:click="updateStation({{ $ass_station }})"
                >
                {{$ass_station->name}}
                <x-slot name="badge">
                    {{$ass_station->activeQueues()->count()}}
                </x-slot>
            </x-filament::tabs.item>

        @endforeach
    </x-filament::tabs>



    <x-filament::section class="shadow-xl mt-1">
        <div class="flex items-center justify-between my-2">
            <h1 class="fi-header-heading text-2xl font-bold tracking-tight text-gray-950 dark:text-white sm:text-3xl">
                {{$station?->name}}
            </h1>
            {{$this->form1}}
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <div class="order-1 lg:order-1 lg:col-span-3 space-y-6">
                <div class="rounded-lg shadow overflow-hidden">
                    <div style="background-color:rgb(66, 34, 247) !important; color:white !important" class="py-3 px-2">
                        <h1 class="text-white text-2xl font-bold px-4 py-2 uppercase">
                            Now Serving
                        </h1>
                    </div>

                    <div class="px-2">
                        <div class="items-center justify-center">
                            <div class="text-center px-4 py-6">
                                <p class="font-bold text-1xl">Queue Number:</p>
                                <span class="text-black font-black" style="font-size: 3rem">{{$currentQueue['queue_number']}}</span>
                                <p class="font-bold text-1xl captitalize">Name: {{$currentQueue['client_name']}}</p>
                            </div>

                        <div class="py-3 px-2 my-2 text-sm bg-gray-100 rounded-lg">
                            <div class="py-2 font-medium text-gray-600 dark:text-gray-300">Required Documents</div>
                            <div class="required-documents p-2 text-gray-900 dark:text-white">{!! $currentQueue['required_documents'] !!}</div>
                        </div>
                        @if ($currentQueue['id'])
                            <div class="flex justify-center m-2">
                                <x-filament::button wire:click="view_queue({{$currentQueue['id']}})" color="primary">
                                    <div class="flex items-center">
                                        <x-fas-eye class="mr-2 w-4 h-4"></x-fas-eye> View
                                    </div>
                                </x-filament::button>
                            </div>
                        @endif


{{--
                        <!-- Transaction Details -->
                        @if ($currentQueue['id'])
                            <x-filament::section class="mb-2">
                                <x-slot name="heading">
                                    <span class="text-base font-semibold text-gray-900 dark:text-white">
                                        Actions
                                    </span>
                                    </x-slot>

                                <dl class="divide-y divide-gray-200 dark:divide-gray-700">
                                    <div class="py-2 flex justify-evenly text-sm">

                                        @if ($currentQueue['status_id'] == 1)
                                            <x-filament::button wire:click="recall_queue({{$currentQueue['id']}})" class="px-6 py-2 !bg-secondary-500">
                                                Call
                                            </x-filament::button>

                                            <x-filament::button wire:click="update_queue({{$currentQueue['id']}},2)" class="px-6 py-2 !bg-primary-500">
                                                Process
                                            </x-filament::button>

                                        @endif
                                        @if ($currentQueue['status_id'] == 2)
                                            <x-filament::button wire:click="update_queue({{$currentQueue['id']}},4)" class="pr-12 pl-12 !bg-primary-500">
                                                Complete
                                            </x-filament::button>
                                            @if ($currentQueue['priority_type_id'] && $currentQueue['status_id'] == 2)
                                                <x-filament::button type="submit" class="px-6 py-2 !bg-danger-500">
                                                    Deprioritize
                                                </x-filament::button>

                                                <x-filament::button wire:click="update_queue({{$currentQueue['id']}},2)" class="px-6 py-2 !bg-primary-500">
                                                    Process
                                                </x-filament::button>

                                            @endif
                                        @endif
                                        <x-filament::button wire:click="update_queue({{$currentQueue['id']}},5)" class="px-6 py-2 !bg-danger-500">
                                            Remove
                                        </x-filament::button>
                                    </div>
                                </dl>
                            </x-filament::section>
                        @endif --}}

                        </div>
                    </div>
                </div>
                <!-- Column 2 (Next + Done) -->
                <div class="order-2 lg:order-2 lg:col-span-1 space-y-6">
                    <!-- Next in Queue -->
                    <div class="rounded-lg shadow overflow-hidden">
                        <div style="background-color:rgb(236, 140, 29) !important; color:white !important" class="py-3 px-2">
                            <h1 class="text-base font-semibold text-white dark:text-white">
                                Next in Queue
                            </h1>
                        </div>
                        <div class="px-2 py-3">
                            <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                                {{-- @dd($station->activeQueues) --}}
                                @if ($station->activeQueues->isEmpty())
                                    <li class="text-gray-500">No active queues</li>

                                @endif
                                @foreach($station->activeQueues as $item)
                                    <li class="flex items-center justify-between">
                                        <span>{{$item->name}}</span><span class="text-xs text-gray-500">{{$item->getQueueNumber()}}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <!-- Done with Transaction -->
                    <div class="rounded-lg shadow overflow-hidden">
                        <div style="background-color:rgb(112, 236, 29) !important; color:white !important" class="py-3 px-2">
                            <h1 class="text-base font-semibold text-white dark:text-white">
                                Completed Transactions
                            </h1>
                        </div>
                        <div class="px-2 py-3">
                            <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                                @if ($station->doneQueues->isEmpty())
                                    <li class="text-gray-500">No active queues</li>
                                @endif
                                @foreach($station->doneQueues as $item)
                                    <li class="flex items-center justify-between">
                                        <span>{{$item->name}}</span><span class="text-xs text-gray-500">{{$item->getQueueNumber()}}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>


                    <div class="rounded-lg shadow overflow-hidden">
                        <div style="!important; color:white !important" class="py-2 px-2 bg-gray-300">
                            <h1 class="text-base font-semibold text-white dark:text-white">
                                Skipped
                            </h1>
                        </div>
                        <div class="px-2 py-3">

                        </div>
                    </div>

                </div>
            </div>
            <!-- Column 2 (Next + Done) -->
            <div class="order-2 lg:order-2 lg:col-span-1 space-y-6">
                <!-- Next in Queue -->
                <div class="rounded-lg shadow overflow-hidden">
                    <div style="background-color:rgb(236, 140, 29) !important; color:white !important" class="py-3 px-2">
                        <h1 class="text-base font-semibold text-white dark:text-white">
                            Next in Queue
                        </h1>
                    </div>
                    <div class="px-2 py-3">
                            <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                            {{-- @dd($station->activeQueues) --}}
                            @if ($station->activeQueues->isEmpty())
                                <li class="text-gray-500">No active queues</li>

                            @endif
                            @foreach($station->activeQueues as $item)
                                <li class="flex items-center justify-between">
                                    <span>{{$item->name}}</span><span class="text-xs text-gray-500">{{$item->getQueueNumber()}}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Done with Transaction -->
                <div class="rounded-lg shadow overflow-hidden">
                    <div style="background-color:rgb(112, 236, 29) !important; color:white !important" class="py-3 px-2">
                        <h1 class="text-base font-semibold text-white dark:text-white">
                            Completed Transactions
                        </h1>
                    </div>
                    <div class="px-2 py-3">
                        <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                            @if ($station->doneQueues->isEmpty())
                                <li class="text-gray-500">No active queues</li>
                            @endif
                            @foreach($station->doneQueues as $item)
                                <li class="flex items-center justify-between">
                                    <span>{{$item->name}}</span><span class="text-xs text-gray-500">{{$item->getQueueNumber()}}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="rounded-lg shadow overflow-hidden">
                    <div style="!important; color:white !important" class="py-2 px-2 bg-gray-300">
                        <h1 class="text-base font-semibold text-white dark:text-white">
                            Skipped
                        </h1>
                    </div>
                    <div class="px-2 py-3">

                    </div>
                </div>

            </div>
        </div>
    </x-filament::section>

    @livewire('queue-modal')
</x-filament::widget>
