
<x-layouts.app>
    <section class="min-h-screen grid grid-rows-[15%_1fr_1%] gap-2 place-content-center bg-no-repeat bg-cover p-2" style ="background-image: url('{{ asset('images/default_front_end/kiosk_bg.png') }}');">
        <div class="p-0 flex items-center justify-center ">
            <img src="{{ asset('images/default_front_end/logo.png') }}" alt="Logo" class="w-64 h-full">
        </div>
        <div id="app">
            @yield('content')
        </div>
        <div >
            <p class="text-black-900 font-bold text-xs self-end">This office follow the Anti-Red Tape Authority(Arta) law. All services are free of fixers. Standart processing times and requirements are poster for your reference.</p>
        </div>
    </section>
    @routes
</x-layouts.app>
