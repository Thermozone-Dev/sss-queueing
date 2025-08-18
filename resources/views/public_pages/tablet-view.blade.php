<section class="min-h-screen flex items-center justify-center bg-no-repeat bg-cover" style ="background-image: url('{{ asset('images/default_front_end/kiosk_bg.png') }}');">
    <div class="w-full max-w-4xl p-3 rounded-lg">
        <div class="p-0 flex items-center justify-center">
            <img src="{{ asset('images/default_front_end/logo.png') }}" alt="Logo" class="w-32 h-24">
        </div>
        @include('public_pages.queue-header',$current_page)

        @include($viewPath)

        <div class="place-self-end">
            <p class="text-black-900 font-bold text-xs self-end">This office follow the Anti-Red Tape Authority(Arta) law. All services are free of fixers. Standart processing times and requirements are poster for your reference.</p>
        </div>
    </div>
</section>

