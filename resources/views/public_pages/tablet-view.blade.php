<section class="min-h-screen grid grid grid-rows-[20%_1fr_3%] place-items-center bg-no-repeat bg-cover" style ="background-image: url('{{ asset('images/default_front_end/kiosk_bg.png') }}');">
    <div class="p-0">
        <img src="{{ asset('images/default_front_end/logo.png') }}" alt="Logo" class="w-full h-full">
    </div>
    <div class="w-full max-w-4xl p-3 rounded-lg min-h-full place-content-stretch grid grid-rows-[10%_1fr]">
        <div>
            @include('public_pages.queue-header',$current_page)
        </div>
        <div class="grid  ">
            @include($viewPath)
        </div>
    </div>
    <div>
        <p class="text-black-900 font-bold text-xs self-end">This office follow the Anti-Red Tape Authority(Arta) law. All services are free of fixers. Standart processing times and requirements are poster for your reference.</p>
    </div>
</section>

