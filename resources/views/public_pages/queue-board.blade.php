{{-- <section class="h-screen p-14 grid grid-cols-4 gap-12 bg-no-repeat bg-cover" style ="background-image: url('{{ asset('images/default_front_end/kiosk_bg.png') }}');"> --}}
<section class="h-screen min-h-screen max-h-screen grid grid-cols-4 gap-0 bg-gray-200 bg-white">
    <div class="min-h-full max-h-full col-span-3 grid gap-0 grid-rows-[24%_1fr_5%] md:grid-rows-[19%_1fr_3%]">
        <div class="bg-transparent flex items-center justify-between text-white px-5">
            <div class="p-0 flex items-center justify-center">
                <img src="{{ asset('images/default_front_end/logo.png') }}" alt="Logo" class="w-48 h-full">
            </div>
            <div class="text-black text-right" wire:poll.60s='getTime'>
                <p class="font-extrabold" style="font-size: 2.5rem">{{$time_now['time']}}</p>
                <p class="font-bold" style="font-size: 1.5rem">{{$time_now['date']}}</p>
            </div>
        </div>
        <div class="bg-gray flex items-center justify-center">
            <div wire:poll.2s="gather_queue_calls" class="hidden">
            </div>
            <div id="videoContainer" class="relative w-full h-full ">
                <video id="video1" class="w-full h-full rounded-t-md p-0 m-0 object-cover" src="{{asset('images/default_front_end/sample_video.mp4')}}"></video>
            </div>
        </div>
        <div class="bg-lime-700 flex items-center justify-center text-white text-xs">
            This office follows the Anti-Red Tape Authority (ARTA) law. All services are free of fixers. Standard processing times and requirements are posted for your reference.
        </div>
    </div>

    {{-- <div class="w-full h-full col-span-1 bg-white rounded-lg" wire:poll.3s='refresh_tickets'> --}}
    <div class="min-h-full min-w-full col-span-1 max-h-full bg-gray-200 rounded-lg grid gap-0 grid-rows-[1fr_8%_25%] md:grid-rows-[1fr_9%_40%]" >
        <div class="grid gap-0 grid-rows-[10%_1fr_1fr_1fr] md:grid-rows-[12%_1fr_1fr_1fr] min-h-full text-white text-sm">
            <div class="bg-white min-h-full flex items-center px-2">
                <p class="font-black text-black" style="font-size: 2.2rem">Now Serving</p>
            </div>
            @foreach ($now_serving as $serving)
                <div class="min-h-full  text-white px-3 py-4 flex items-center justify-between" style="background-color: {{$serving['bg_color']}}">
                        <!-- Left Section -->
                    <div>
                        <p class="text-md font-semibold uppercase tracking-wide">{{$serving['stations_name']}} - {{$serving['name']}}</p>
                        <div class="flex items-baseline gap-3">
                        <span class="font-extrabold text-[2.8rem] leading-none ">{{$serving['queue_number']}} </span>
                        </div>
                    </div>
                    <!-- Right Section -->
                    <div class="text-right">
                        <p class="text-md font-semibold uppercase tracking-wide">Window</p>
                        <span class="font-extrabold text-[2.8rem] leading-none">{{$serving['station_code']}}</span>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="min-h-full text-white text-sm bg-white px-2 py-3">
            <div class="min-h-full py-3">
                <p class="font-black text-black uppercase" style="font-size: 1.2rem">Next in line</p>
                <p class="font-none text-black text-sm">Please prepare your document before your number is called.</p>
            </div>
        </div>

        <div class="bg-white min-h-full text-black col-span-1 grid place-content-stretch px-1">

            <div class="overflow-x-auto shadow-md sm:rounded-sm min-h-full max-h-full min-w-full text-white text-sm">
                <table class="w-full text-sm text-left rtl:text-right text-black">
                    <thead>
                        <tr class="bg-white text-gray-700" style="font-size: 0.8rem">
                            @foreach ($queues as $queue)
                                <th scope="col" class="px-1 py-3 text-left font-semibold uppercase tracking-wider">{{ $queue['station']->name ?? $queue['station']->code }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $maxQueues = collect($queues)->map(fn($q) => $q['queues']->count())->max();
                        @endphp
                        @for ($i = 0; $i < $maxQueues; $i++)
                            <tr class="odd:bg-gray-200 even:bg-white">
                                @foreach ($queues as $q)
                                    @php
                                        $queue = $q['queues']->get($i);
                                        $station = $q['station'];
                                    @endphp
                                    <td class="px-2 py-0.5">
                                        @if ($queue)
                                            <p class="uppercase text-black font-medium text-[0.5rem]">{{ $queue->transaction->name}} - {{$queue->name}}</p>
                                            <p class="uppercase font-black text-[1.3rem]" >{{$queue->getQueueNumber()}}</p>
                                        @else
                                            -
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($showModal && $modalDetails)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 "  wire:poll.10s="closeModal">
            <div class="text-center shadow rounded-2xl p-8 relative" style="background-color: {{$serving['bg_color']}}">
                <audio id="modalSound" src="{{asset('images/default_front_end/call_number_sound.wav')}}" autoplay loop></audio>
                <span class="text-white font-black" style="font-size: 6rem">{{$modalDetails['queue_number']}}</span>
                <p class="font-bold text-2xl captitalize">{{$modalDetails['transaction']}}</p>
            </div>
        </div>
    @endif

</section>

<script>
    ["DOMContentLoaded", "click"].forEach(evt =>
        document.addEventListener(evt, () => {

            const videos = document.querySelectorAll('#videoContainer video');
            const playButton = document.getElementById('playButton');
            let currentVideo = 0;

            // Check if videos exist
            if (videos.length === 0) return;

            // Hide all videos initially except the first one
            videos.forEach((video, index) => {
                video.style.display = index === 0 ? 'block' : 'none';
            });

            function playNextVideo() {
                videos[currentVideo].style.display = 'none';
                videos[currentVideo].pause();
                currentVideo = (currentVideo + 1) % videos.length;
                videos[currentVideo].style.display = 'block';
                videos[currentVideo].play();
            }

            videos.forEach(video => {
                video.addEventListener('ended', playNextVideo);
            });

            // Hide play button and play the first video on load
            if (playButton) {
                playButton.style.display = 'none';
            }
            videos[0].play();
        })
    );


    document.addEventListener('open-modal', () => {
        let audio = document.getElementById('modalSound');

        // Restart and play sound
        audio.currentTime = 0;
        audio.play();

        // Auto close modal after 3 seconds
        setTimeout(() => {
            $this.closeModal();
            audio.pause(); // stop sound when modal closes
        }, 3000);
    });

</script>
