<div class="flex flex-col justify-center items-center w-full h-screen px-6">

    <div class="w-full max-w-sm text-center">

        <h1 class="text-2xl font-bold text-slate-900">
            Welcome to SSS Online Services
        </h1>

        <p class="text-sm text-slate-500 mt-2">
            Choose how you want to continue.
        </p>

        <div class="grid grid-cols-1 gap-3 mt-8">

            {{-- LOGIN --}}
            <a href="{{ route('login') }}"
                class="w-full rounded-xl bg-blue-600 text-white px-5 py-4 text-sm font-semibold
                       hover:bg-blue-700 transition text-center">
                Login
            </a>

            {{-- REGISTER --}}
            <a href="{{ route('register') }}"
                class="w-full rounded-xl border border-slate-300 bg-white text-slate-700 px-5 py-4
                       text-sm font-semibold hover:bg-slate-50 transition text-center">
                Register
            </a>

        </div>

    </div>

</div>
