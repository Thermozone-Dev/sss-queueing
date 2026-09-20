<template>
    <div
        v-if="show"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
    >
        <div
            class="w-full max-w-md space-y-5 rounded-2xl bg-white p-6 shadow-xl"
        >
            <div class="space-y-2">
                <div
                    class="flex size-12 items-center justify-center rounded-full bg-red-100 text-red-600"
                >
                    <CircleAlert
                        class="h-6 w-6"
                        :stroke-width="2"
                    />
                </div>

                <h2 class="text-xl font-bold text-[#505050]">
                    Confirm Queue Action
                </h2>

                <p class="text-sm leading-5 text-[#606060]">
                    You are about to proceed with this appointment.
                </p>
            </div>

            <div class="space-y-3 text-sm text-[#606060]">
                <p class="font-semibold text-yellow-600">
                    Once confirmed:
                </p>

                <ul class="list-inside list-disc space-y-2">
                    <li>
                        This appointment will be marked as
                        <strong>Processing</strong>.
                    </li>

                    <li>
                        You cannot queue again using this appointment.
                    </li>

                    <li>
                        To re-queue, you must contact the administrator.
                    </li>
                </ul>

                <p class="text-xs text-gray-500">
                    Please confirm if you wish to continue.
                </p>
            </div>

            <div
                class="flex flex-col-reverse justify-end gap-3 pt-2 sm:flex-row"
            >
                <button
                    type="button"
                    @click="$emit('close')"
                    class="rounded-lg bg-gray-100 px-5 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-200"
                >
                    Cancel
                </button>

                <button
                    type="button"
                    @click="$emit('confirm')"
                    class="rounded-lg bg-red-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-red-700"
                >
                    Confirm & Proceed
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import { CircleAlert } from 'lucide-vue-next';

export default {
    components: {
        CircleAlert,
    },

    props: {
        show: {
            type: Boolean,
            default: false,
        },
    },

    emits: [
        'close',
        'confirm',
    ],
};
</script>