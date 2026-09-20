<template>
    <section class="space-y-3">
        <!-- Label -->
        <div class="mx-auto flex w-full items-center gap-3 md:w-[85%]">
            <label
                for="appointment-id"
                class="shrink-0 text-sm font-bold text-[#606060]"
            >
                Appointment ID
                <span class="text-red-500">*</span>
            </label>

            <hr class="flex-1 border-[#C0BBBB]/60" />
        </div>

        <!-- Search Bar -->
        <div class="mx-auto w-full md:w-[85%]">
            <div
                class="flex items-center gap-3 rounded-xl border border-[#D6E4FF] bg-white px-3 py-2 shadow-[0_8px_24px_0_rgba(30,80,161,0.078)] md:px-4"
            >
                <!-- Search Icon -->
                <div class="shrink-0 rounded-full bg-[#EEF4FF] p-2">
                    <Search class="h-4 w-4 text-[#3350E8]" />
                </div>

                <!-- Input -->
                <div class="min-w-0 flex-1">
                    <input
                        id="appointment-id"
                        type="text"
                        :value="modelValue"
                        @input="$emit('update:modelValue', $event.target.value)"
                        @keyup.enter="$emit('search')"
                        placeholder="APP-2026-01-0001"
                        maxlength="20"
                        class="w-full border-none bg-transparent text-sm text-[#505050] outline-none placeholder:text-gray-400 focus:border-none focus:outline-none focus:ring-0"
                    />
                </div>

                <!-- Search Button -->
                <button
                    type="button"
                    @click="$emit('search')"
                    :disabled="loading"
                    class="flex shrink-0 items-center gap-2 rounded-lg bg-[#3350E8] px-3 py-2 text-sm font-bold text-white transition hover:bg-[#2842C7] disabled:cursor-not-allowed disabled:opacity-60 md:px-5"
                >
                    <span class="hidden sm:inline">
                        {{ loading ? 'Searching...' : 'Search' }}
                    </span>

                    <ChevronRight
                        v-if="!loading"
                        :size="18"
                        :stroke-width="2.5"
                    />

                    <LoaderCircle
                        v-else
                        :size="16"
                        :stroke-width="2.5"
                        class="animate-spin"
                    />
                </button>
            </div>

            <!-- Error -->
            <p
                v-if="error"
                class="mt-1 px-2 text-xs text-red-500"
            >
                {{ error }}
            </p>
        </div>
    </section>
</template>

<script>
import {
    Search,
    ChevronRight,
    LoaderCircle,
} from 'lucide-vue-next';

export default {
    components: {
        Search,
        ChevronRight,
        LoaderCircle,
    },

    props: {
        modelValue: {
            type: String,
            default: '',
        },

        loading: {
            type: Boolean,
            default: false,
        },

        error: {
            type: String,
            default: null,
        },
    },

    emits: [
        'update:modelValue',
        'search',
    ],
};
</script>