<x-layouts.app>
    <?php
        $settings = app(App\Settings\GeneralSettings::class);
        $url = $settings->site_youtube;
        $videoId = null;
        // Match YouTube watch links
        if (preg_match('/watch\?v=([^\&\?]+)/', $url, $matches)) {
            // YouTube watch URL
            $videoId = $matches[1];
            $url = "https://www.youtube.com/embed/" . $videoId;
        } elseif (preg_match('/youtu\.be\/([^\&\?]+)/', $url, $matches)) {
            // Short YouTube URL
            $videoId = $matches[1];
            $url = "https://www.youtube.com/embed/" . $videoId;
        } elseif (str_contains($url, 'youtube.com/embed/')) {
            // Already an embed link
            $videoId = basename($url); // get last segment as ID
        } else {
            // Not a YouTube link
            $url = null;
        }
        $logo = Storage::url($settings->brand_logo);

        $theme  = $settings->site_theme;

    ?>
    <section id="app2" class="min-h-screen max-h-screen grid grid-cols-4 gap-0" style="background: {{$settings->site_theme['primary']}}">
        <div class="col-span-3 grid gap-0 grid-rows-[20%_1fr_2%]">
            <div class="bg-transparent flex items-center justify-between text-white px-5">
                <div class="p-3 w-auto h-48">
                    <img src="{{ $logo ?? asset('images/default_front_end/logo.png') }}" alt="Logo" class="w-full h-full object-contain">
                </div>
                <div class="text-right" style="color: {{$settings->site_theme['secondary']}}">
                    <p class="font-extrabold" id="clock" style="font-size: 2rem"></p>
                    <p class="font-bold" id="date" style="font-size: 1rem"></p>
                </div>
            </div>
            <div class="flex items-center justify-center overflow-hidden relative " style="background-color:green;">
                <iframe
                    class="absolute  w-full h-full rounded-t-md p-0 m-0 object-cover"
                    src="{{ $url }}?autoplay=1&loop=1&playlist={{ $videoId }}"
                    frameborder="0"
                    allow="autoplay; encrypted-media"
                    allowfullscreen>
                </iframe>
            </div>
            <div class="flex items-center justify-center text-white text-xs" style="background-color: {{$settings->site_theme['primary']}}">
                This office follows the Anti-Red Tape Authority (ARTA) law. All services are free of fixers. Standard processing times and requirements are posted for your reference.
            </div>
        </div>


        <div class="min-h-full min-w-full col-span-1 max-h-full bg-gray-200 rounded-lg grid gap-0 grid-rows-[8%_1fr] md:grid-rows-[8%_1fr]" >
            {{-- <now-serving></now-serving> --}}
            <div class="min-h-full text-white text-sm bg-white px-2 py-3">
                <div class="min-h-full py-3">
                    <p class="font-black text-black uppercase" style="font-size: 1.3rem">Next in line</p>
                    <p class="font-none text-black text-sm">Please prepare your document before your number is called.</p>
                </div>
            </div>
            <next-in-line></next-in-line>
        </div>
        <queue-call />
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
        window.appTheme = @json($theme);

        updateDateTime();
    </script>

</x-layouts.app>

