<div>
    @if ($queue)
        <x-filament::modal id="queue-modal-view"

            slide-over width="xl">
            <x-slot name="heading" class="text-center">
                <span class="text-xl">
                    Queue - {{$queue->getQueueNumber()}}
                </span>
            </x-slot>

            <div class="p-2 bg-gray-100 border rounded-md">
                <h2 class="mb-2 text-md font-medium text-heading">Client Info:</h2>
                <div class="pl-8">
                    <span class="mb-2 text-md  text-heading mr-2">Name: </span> <span class="mb-2 text-md  text-heading">{{ucfirst($queue->name)}}</span> <br>
                    <span class="mb-2 text-md  text-heading mr-2">Queue Number: </span> <span class="mb-2 text-md  text-heading">{{$queue->getQueueNumber()}}</span> <br>
                    <span class="mb-2 text-md  text-heading mr-2">Status: </span>
                        @if ($queue->status_id == 1 || $queue->status_id == 2)
                            <span class="bg-warning-soft border border-warning-subtle text-fg-warning text-xs font-medium px-1.5 py-0.5 rounded">{{$queue->status->name}}</span>
                        @endif
                        @if ($queue->status_id == 4)
                            <span class="bg-success-soft border border-success-subtle text-fg-success-strong text-xs font-medium px-1.5 py-0.5 rounded">{{$queue->status->name}}</span>
                        @endif
                        @if ($queue->status_id == 5)
                            <span class="bg-danger-soft border border-danger-subtle text-fg-danger-strong text-xs font-medium px-1.5 py-0.5 rounded">{{$queue->status->name}}</span>
                        @endif<br>
                        @if ($queue->priority)
                            <span class="mb-2 text-md  text-heading mr-2">Prioritization: </span> <span class="mb-2 text-md  text-heading">{{$queue->priority->name}}</span> <br>
                        @endif
                    <span class="mb-2 text-md  text-heading mr-2">Queued At: </span> <span class="mb-2 text-md  text-heading">{{Carbon\Carbon::parse($queue->created_at)->isoFormat('MMMM DD, Y H:m A')}}</span><br>
                </div>
            </div>

            <div class="p-4 bg-gray-100 border rounded-md">
                <h2 class="text-md font-medium text-heading ">Transaction Steps:</h2>
                <div class="mt-4 relative overflow-x-auto bg-neutral-primary shadow-xs rounded-base border rounded-sm">
                    <table class="w-full text-sm text-left rtl:text-right text-body">
                        <thead class="text-sm text-body border-b border-default">
                            <tr>
                                <th scope="col" class="px-6 py-3 bg-neutral-secondary-soft font-medium">
                                    Step Name
                                </th>
                                <th scope="col" class="px-6 py-3 font-medium">
                                    Station
                                </th>
                                <th scope="col" class="px-6 py-3 font-medium">
                                    Status
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($queue->queueSteps as $step)
                                <tr class="border-b  border-default">
                                    <th scope="row" class="px-6 py-4 font-medium text-heading whitespace-nowrap bg-neutral-secondary-soft">
                                        {{$step->transaction_step->title}}
                                    </th>
                                    <td class="px-6 py-4">
                                        {{$step->station->name}}
                                    </td>
                                    <td class="px-6 py-4 flex items-center">
                                        @if ($step->queue_step_status_id == 4)
                                            <x-fas-circle-check class="w-4 h-4 mr-2 text-green-500"></x-fas-circle-check> {{$step->status->name}}
                                        @endif
                                        @if ($step->queue_step_status_id == 1 || $step->queue_step_status_id == 2)
                                            <x-fas-clock class="w-4 h-4 mr-2 text-yellow-500"></x-fas-clock> {{$step->status->name}}
                                        @endif
                                        @if ($step->queue_step_status_id == 5)
                                            <x-fas-circle-xmark class="w-4 h-4 mr-2 text-danger-500"></x-fas-circle-xmark> {{$step->status->name}}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <x-slot name="footerActions" >
                <x-filament::section class="mb-2 w-full" >
                    <x-slot name="heading">
                        <span class="text-base font-semibold text-gray-900 dark:text-white">
                            Actions
                        </span>
                    </x-slot>
                    <div class="grid grid-cols-4 gap-2">

                        @if ($queue->status_id == 1)
                            <x-filament::button icon="fas-phone-alt" color="warning" wire:click="recall_queue">
                                Call
                            </x-filament::button>

                            <x-filament::button  icon="fas-play" color="success" wire:click="update_queue(2)">
                                Process
                            </x-filament::button>
                        @endif

                        @if ($queue->status_id == 2)
                            <x-filament::button  icon="fas-circle-check" color="primary" wire:click="update_queue(4)" >
                                Complete
                            </x-filament::button>
                            @if ($queue->priority)
                                <x-filament::button  icon="fas-arrow-circle-down" color="danger">
                                    Deprioritize
                                </x-filament::button>
                            @else
                                <x-filament::button  icon="fas-arrow-circle-up" color="success">
                                    Prioritize
                                </x-filament::button>
                            @endif
                        @endif
                        <x-filament::button icon="fas-hand" color="warning">
                            Skip
                        </x-filament::button>

                        <x-filament::button  icon="fas-trash" color="danger" wire:click="update_queue(5)">
                            Remove
                        </x-filament::button>
                    </div>
                </x-filament::section>
            </x-slot>

            {{-- Modal content --}}
        </x-filament::modal>
    @endif

    @if ($showCompleteConfirmationModal)
        <x-filament::modal
            id="complete-confirmation-modal"
            icon="fas-exclamation-triangle"
            width="lg" :close-button="false">
            <x-slot name="heading">
                All Steps Completed
            </x-slot>

            <div>
                <p class="text-md ">All required transaction steps for this queue has all completed the system will completely mark this queue as completed</p>
            </div>

            <x-slot name="footerActions" class="flex justify-center">
                <x-filament::button color="primary" wire:click="$set('showCompleteConfirmationModal', false)">
                    OK
                </x-filament::button>

                <x-filament::button color="primary" wire:click="$dispatch('close-modal', { id: complete-confirmation-modal })">
                    Cancel
                </x-filament::button>
            </x-slot>
        </x-filament::modal>
    @endif

</div>