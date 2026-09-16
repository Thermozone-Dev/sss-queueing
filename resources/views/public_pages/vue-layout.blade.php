
<x-layouts.app>
    <section class="min-h-screen px-12 py-6 ">
        @php
            $settings = app(App\Settings\GeneralSettings::class);

            $logo = Storage::url($settings->brand_logo);
        @endphp

        {{-- <div class="p-0 flex items-center justify-center ">
            <img src="{{ $logo ?? asset('images/default_front_end/logo.png')  }}" alt="Logo" class="w-64 h-full object-contain">
        </div> --}}
        <main id="app" class="mb-2 min-h-full">
            <kiosk-header></kiosk-header>
            @yield('content')
            <progress-overlay ref="progressOverlay"></progress-overlay>
        </main>
        <hr>
        <footer class="p-6 flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" 
                width="24" height="24"
                viewBox="0 0 24 24" fill="none" 
                stroke="currentColor" stroke-width="2" 
                stroke-linecap="round" stroke-linejoin="round" 
                class="lucide lucide-shield-check text-paragraph">
                <path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4
                 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 
                 17 5 19 5a1 1 0 0 1 1 1z"/>
                <path d="m9 12 2 2 4-4"/></svg>
            <p class="text-xs text-paragraph pr-32">This office observes the Anti-Red Tape Act (R.A. 9485). No fixers allowed. All transactions are free of charge. Report any irregularity 
                    to ARTA: 1-ARTA (2782). • Data Privacy Notice: Your information is protected under R.A. 10173.</p>
        </footer>
    </section>
    @routes
</x-layouts.app>
