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
    <section id="app2" class="h-screen flex flex-col" style="background: {{$settings->site_theme['primary']}}">

        <div class="h-[10%] flex items-center justify-between text-white px-5">
            <!-- Logo -->
            <div class="h-full flex items-center">
                <img
                    src="{{ $logo ?? asset('images/default_front_end/logo.png') }}"
                    alt="Logo"
                    class="h-full w-auto object-contain"
                >
            </div>
            <!-- Clock -->
            <div class="text-right" style="color: {{$settings->site_theme['secondary']}}">
                <p class="font-extrabold text-2xl" id="clock"></p>
                <p class="font-bold text-sm" id="date"></p>
            </div>

        </div>

        <!-- Video -->
        <div class="flex-1  relative overflow-hidden">
            <iframe
                class="absolute w-full h-full object-cover"
                src="{{ $url }}?autoplay=1&loop=1&playlist={{ $videoId }}"
                frameborder="0"
                allow="autoplay; encrypted-media"
                allowfullscreen>
            </iframe>
        </div>

        <!-- Main Content -->
        <div class="bg-gray-200 overflow-hidden">
            <next-in-line></next-in-line>
            <queue-call />
        </div>

        <!-- Footer -->
        <div class="h-[2%] flex items-center justify-center text-white text-xs">
            This office follows the Anti-Red Tape Authority (ARTA) law. All services are free of fixers. Standard processing times and requirements are posted for your reference.
        </div>

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

