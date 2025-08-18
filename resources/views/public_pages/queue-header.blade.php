<style scoped>
    .pill-btn {
        border: 2px solid #84CC16;    /* lime-500 border */
        background-color: transparent; /* transparent background */
        color: #84CC16;               /* text color same as border */
    }
</style>
@if ($page_show_prev_button)
    <button wire:click="back_button" class="flex pill-btn items-center px-4 py-1 rounded-lg mb-5">
        <x-fas-arrow-left class="w-4 h-4 mr-2"></x-fas-arrow-left>Back
    </button>
@endif

@if ($display_header_text)
    <h1 class="text-3xl font-extrabold text-black">{{$page_header}}</h1>
    <p class="text-gray-600">{{$page_description}}</p>
@endif