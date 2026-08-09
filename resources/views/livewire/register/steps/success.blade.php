<div class="max-w-md mx-auto text-center py-10 h-screen">

    <div class="text-6xl mb-4">
        ✅
    </div>

    <h2 class="text-3xl font-bold text-green-600 mb-2">
        Success!
    </h2>

    <p class="text-gray-600 mb-6">
        Your account has been created successfully.
    </p>

    @if (session()->has('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('filament.admin.auth.login') }}"
        class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition text-sm">
        Go to Login
    </a>

</div>
