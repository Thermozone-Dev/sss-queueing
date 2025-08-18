<x-filament::widget>
    <x-filament::section class="shadow-xl">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <div class="order-1 lg:order-1 lg:col-span-3 space-y-6">
                <x-filament::section>
                    <x-slot name="heading">
                        <div class="flex items-center justify-between">
                            <h1 class="fi-header-heading text-2xl font-bold tracking-tight text-gray-950 dark:text-white sm:text-3xl">
                                {{$station?->name}}
                            </h1>
                           {{$this->form1}}
                        </div>
                    </x-slot>

                    <dl class="divide-y divide-gray-200 dark:divide-gray-700">
                        <div class="py-3 flex justify-between text-sm">
                            <dt class="font-medium text-gray-600 dark:text-gray-300">Queue ID</dt>
                            <dd class="text-gray-900 dark:text-white">{{$currentQueue['queue_number']}}</dd>
                        </div>
                        <div class="py-3 flex justify-between text-sm">
                            <dt class="font-medium text-gray-600 dark:text-gray-300">Client Name</dt>
                            <dd class="text-gray-900 dark:text-white">{{$currentQueue['client_name']}}</dd>
                        </div>
                        <div class="py-3 flex justify-between text-sm">
                            <dt class="font-medium text-gray-600 dark:text-gray-300">Queue Status</dt>
                            <dd class="text-info-600 dark:text-info-400 font-semibold">{{$currentQueue['queue_status']}}</dd>
                        </div>
                        <div class="py-3 text-sm">
                            <div class="py-2 font-medium text-gray-600 dark:text-gray-300">Required Documents</div>
                            <div class="required-documents p-2 text-gray-900 dark:text-white">{!! $currentQueue['required_documents'] !!}</div>
                        </div>
                    </dl>


                     <!-- Transaction Details -->
                    @if ($currentQueue['id'])
                        <x-filament::section>
                            <x-slot name="heading">
                                <span class="text-base font-semibold text-gray-900 dark:text-white">
                                    Actions
                                </span>
                            </x-slot>

                            <dl class="divide-y divide-gray-200 dark:divide-gray-700">
                                <div class="py-2 flex justify-evenly text-sm">
                                    @if ($currentQueue['status_id'] == 1)
                                        <x-filament::button wire:click="call_queue({{$currentQueue['id']}})" class="px-6 py-2 !bg-warning-500">
                                            Call Next
                                        </x-filament::button>

                                        <x-filament::button wire:click="recall_queue({{$currentQueue['id']}})" class="px-6 py-2 !bg-secondary-500">
                                            Recall
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
                                        @else
                                            <x-filament::button type="submit" class="px-6 py-2 !bg-primary-500">
                                                Prioritize
                                            </x-filament::button>
                                        @endif
                                    @endif
                                    <x-filament::button wire:click="update_queue({{$currentQueue['id']}},5)" class="px-6 py-2 !bg-danger-500">
                                        Remove
                                    </x-filament::button>

                                </div>
                            </dl>
                        </x-filament::section>
                    @endif

                </x-filament::section>
            </div>
            <!-- Column 2 (Next + Done) -->
            <div class="order-2 lg:order-2 lg:col-span-1 space-y-6">
                <!-- Next in Queue -->
                <x-filament::section>
                    <x-slot name="heading">
                        <span class="text-base font-semibold text-gray-900 dark:text-white">
                            Next in Queue
                        </span>
                    </x-slot>

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
                </x-filament::section>

                <!-- Done with Transaction -->
                <x-filament::section>
                    <x-slot name="heading">
                        <span class="text-base font-semibold text-gray-900 dark:text-white">
                            Done with Transaction
                        </span>
                    </x-slot>

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
                </x-filament::section>
            </div>
        </div>
    </x-filament::section>
</x-filament::widget>
