
<x-layouts.app>
    <section class="min-h-screen grid grid-rows-[15%_1fr_1%] gap-2 place-content-center bg-no-repeat bg-cover p-2">
        @php
            $settings = app(App\Settings\GeneralSettings::class);

            $logo = Storage::url($settings->brand_logo);
        @endphp

        <div class="p-0 flex items-center justify-center ">
            <img src="{{ $logo ?? asset('images/default_front_end/logo.png')  }}" alt="Logo" class="w-64 h-full object-contain">
        </div>
        <div id="app">
            @yield('content')
            <progress-overlay ref="progressOverlay"></progress-overlay>
        </div>
        <div >
            <p class="text-black-900 font-bold text-xs self-end">This office follow the Anti-Red Tape Authority(Arta) law. All services are free of fixers. Standard processing times and requirements are poster for your reference.</p>
        </div>
    </section>
    @routes
</x-layouts.app>
