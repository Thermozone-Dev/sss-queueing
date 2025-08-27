<x-layouts.app>
    <section id="app2" class="min-h-screen max-h-screen grid grid-cols-4 gap-0 bg-gray-200">
        <div class="col-span-3 grid gap-0 grid-rows-[20%_1fr_2%]">
            <div class="bg-transparent flex items-center justify-between text-white px-5">
                <div class="p-0 flex items-center justify-center">
                    <img src="{{ asset('images/default_front_end/logo.png') }}" alt="Logo" class="w-64 h-full">
                </div>
                <div class="text-black text-right">
                    <p class="font-extrabold" id="clock" style="font-size: 2rem"></p>
                    <p class="font-bold" id="date" style="font-size: 1rem"></p>
                </div>
            </div>
            <div id="videoContainer" class="bg-gray flex items-center justify-center overflow-hidden relative " style="background-color:green;">
                <video id="video1" class="absolute  w-full h-full rounded-t-md p-0 m-0 object-cover" src="{{asset('images/default_front_end/sample_video.mp4')}}"></video>
            </div>
            <div class="bg-lime-700 flex items-center justify-center text-white text-xs">
                This office follows the Anti-Red Tape Authority (ARTA) law. All services are free of fixers. Standard processing times and requirements are posted for your reference.
            </div>
        </div>


        <div class="min-h-full min-w-full col-span-1 max-h-full bg-gray-200 rounded-lg grid gap-0 grid-rows-[1fr_8%_25%] md:grid-rows-[1fr_9%_40%]" >
            <now-serving></now-serving>
            <div class="min-h-full text-white text-sm bg-white px-2 py-3">
                <div class="min-h-full py-3">
                    <p class="font-black text-black uppercase" style="font-size: 1.2rem">Next in line</p>
                    <p class="font-none text-black text-sm">Please prepare your document before your number is called.</p>
                </div>
            </div>
            <next-in-line></next-in-line>
        </div>
        {{-- @if($showModal && $modalDetails)
            <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 "  wire:poll.10s="closeModal">
                <div class="text-center shadow rounded-2xl p-8 relative" style="background-color: #84CC16">
                    <audio id="modalSound" src="{{asset('images/default_front_end/call_number_sound.wav')}}" autoplay loop></audio>
                    <span class="text-white font-black" style="font-size: 6rem">{{$modalDetails['queue_number']}}</span>
                    <p class="font-bold text-2xl captitalize">{{$modalDetails['transaction']}}</p>
                </div>
            </div>
        @endif --}}
    </section>

    @routes
    <script>
        function updateDateTime() {
            const now = new Date();
            // Format Time (12-hour with meridiem)
            let hours = now.getHours();
            const minutes = String(now.getMinutes()).padStart(2, "0");
            const seconds = String(now.getSeconds()).padStart(2, "0");
            const meridiem = hours >= 12 ? "PM" : "AM";
            hours = hours % 12 || 12; // convert to 12-hour format
            const formattedHours = String(hours).padStart(2, "0");
            const timeString = `${formattedHours}:${minutes}:${seconds} ${meridiem}`;
            // Format Date (12 August 2025)
            const options = { day: "numeric", month: "long", year: "numeric" };
            const dateString = now.toLocaleDateString("us-EN", options);
            // Update DOM
            document.getElementById("clock").textContent = timeString;
            document.getElementById("date").textContent = dateString;
        }
        // Update every second
        setInterval(updateDateTime, 1000);
        updateDateTime();

        ["click"].forEach(evt =>
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

</x-layouts.app>

