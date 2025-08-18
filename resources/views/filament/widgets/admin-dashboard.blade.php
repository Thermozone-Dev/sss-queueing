<x-filament::widget>
    <x-filament::section class="shadow-xl">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @foreach($stations as $station)
                <div class="space-y-6 mb-5">
                    <x-filament::section class="shadow-md">
                        <x-slot name="heading">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{$station->name}}</h3>
                                </div>
                                <div>
                                    <span class="px-3 py-1 text-xs font-semibold text-white !bg-gray-500 rounded-full">{{$station->code}}</span>
                                    @if($station->status == 1)
                                        <span class="px-3 py-1 text-xs font-semibold text-white bg-green-500 rounded-full">Active</span>
                                    @else
                                        <span class="px-3 py-1 text-xs font-semibold text-white bg-red-500 rounded-full">Inactive</span>
                                    @endif
                                </div>
                            </div>
                        </x-slot>

                        <div class="p-2 text-sm text-center text-gray-700 dark:text-gray-300">
                            <div class="p-4 bg-gray-100 rounded-lg">
                                <div class="text-sm">
                                    <span class="text-3xl font-semibold text-gray-600">QUEUE-023</span>
                                    <div class="text-sm text-gray-600">Currently Serving</div>
                                </div>
                            </div>
                            <div class="mt-5 space-y-4 lg:space-y-0 lg:flex lg:space-x-4 lg:flex-row">
                                <x-filament::button class="w-full !bg-warning-600 text-white">
                                    <div class="mr-2">{{$station->queues->count()}}</div> Total
                                </x-filament::button>
                                <x-filament::button class="w-full !bg-indigo-600 text-white">
                                    <div class="mr-2">{{$station->activeQueues->count()}}</div> Active
                                </x-filament::button>

                                <x-filament::button class="w-full !bg-red-500 text-white">
                                    <div class="mr-2">{{$station->processingQueues->count()}}</div> Processing
                                </x-filament::button>

                                <x-filament::button class="w-full !bg-gray-500 text-white">
                                    <div class="mr-2">{{$station->pendingQueues->count()}}</div> Pending
                                </x-filament::button>
                            </div>

                        </div>
                    </x-filament::section>
                </div>
            @endforeach
        </div>
    </x-filament::section>
</x-filament::widget>
