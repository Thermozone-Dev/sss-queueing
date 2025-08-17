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
                            <dd class="text-gray-900 dark:text-white">#{{$queue_number}}</dd>
                        </div>
                        <div class="py-3 flex justify-between text-sm">
                            <dt class="font-medium text-gray-600 dark:text-gray-300">Client Name</dt>
                            <dd class="text-gray-900 dark:text-white">{{$clent_name}}</dd>
                        </div>
                        <div class="py-3 flex justify-between text-sm">
                            <dt class="font-medium text-gray-600 dark:text-gray-300">Queue Status</dt>
                            <dd class="text-info-600 dark:text-info-400 font-semibold">{{$queue_status}}</dd>
                        </div>
                        <div class="py-3 text-sm">
                            <div class="py-2 font-medium text-gray-600 dark:text-gray-300">Required Documents</div>
                            <div class="required-documents p-2 text-gray-900 dark:text-white">{!! $required_documents !!}</div>
                        </div>
                    </dl>
                </x-filament::section>

                <!-- Transaction Details -->
                <x-filament::section>
                    <x-slot name="heading">
                        <span class="text-base font-semibold text-gray-900 dark:text-white">
                            Actions
                        </span>
                    </x-slot>

                    <dl class="divide-y divide-gray-200 dark:divide-gray-700">
                        <div class="py-3 flex justify-between text-sm">
                            <x-filament::button type="submit" class="pr-12 pl-12 !bg-warning-500">
                                Call Next
                            </x-filament::button>
                            <x-filament::button type="submit" class="pr-12 pl-12 !bg-info-500">
                                Skip
                            </x-filament::button>
                            <x-filament::button type="submit" class="pr-12 pl-12 !bg-primary-500">
                                Prioritize
                            </x-filament::button>
                            <x-filament::button type="submit" class="pr-12 pl-12 !bg-danger-500">
                                Deprioritize
                            </x-filament::button>
                        </div>
                    </dl>
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
                        @foreach($nextQueues as $item)
                            <li class="flex items-center justify-between">
                                <span>{{$item['client_name']}}</span><span class="text-xs text-gray-500">{{$item['queue_number']}}</span>
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
                        @foreach($nextQueues as $item)
                            <li class="flex items-center justify-between">
                                <span>{{$item['client_name']}}</span><span class="text-xs text-gray-500">{{$item['queue_status2']}}</span>
                            </li>
                        @endforeach
                    </ul>
                </x-filament::section>
            </div>
        </div>
    </x-filament::section>
</x-filament::widget>
