<template>
    <section
        class="flex min-h-[440px] flex-col rounded-2xl border border-[#D6E4FF] bg-white px-5 py-5 md:px-6 xl:col-span-2"
    >
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="w-8"></div>

            <h2 class="text-center font-bold text-[#505050]">
                Appointment Details
            </h2>

            <div
                class="rounded-full border border-[#D6E4FF] bg-[#D6E4FF]/60 p-2 text-[#1E50A1]"
            >
                <UserRound
                    :size="18"
                    :stroke-width="2"
                />
            </div>
        </div>

        <!-- Appointment -->
        <div
            v-if="appointment"
            class="mt-6 flex-1 space-y-4"
        >
            <div class="space-y-3 rounded-xl bg-[#F8FAFF] p-4">
                <div>
                    <p class="text-xs text-[#99A1AF]">
                        APPOINTMENT ID
                    </p>

                    <p class="break-all font-bold text-[#1E50A1]">
                        {{ appointment.code }}
                    </p>
                </div>

                <div>
                    <p class="text-xs text-[#99A1AF]">
                        FULL NAME
                    </p>

                    <p class="font-semibold text-[#505050]">
                        {{ appointment.name }}
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <p class="text-xs text-[#99A1AF]">
                            DATE
                        </p>

                        <p class="text-sm font-semibold text-[#505050]">
                            {{ appointment.date }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-[#99A1AF]">
                            TIME
                        </p>

                        <p class="text-sm font-semibold text-[#505050]">
                            {{ formatTime(appointment.time) }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div
                class="flex items-center justify-between gap-2 rounded-lg border border-[#D6E4FF] px-3 py-3"
            >
                <span class="text-sm font-semibold text-[#606060]">
                    Appointment Status
                </span>

                <span
                    class="rounded-full px-3 py-1 text-xs font-bold capitalize"
                    :class="statusClass"
                >
                    {{ appointment.status.toUpperCase() }}
                </span>
            </div>
        </div>

        <!-- Empty -->
        <div
            v-else
            class="flex flex-1 flex-col items-center justify-center py-10"
        >
            <div
                class="rounded-full border-2 border-dashed border-[#D6E4FF] bg-[#D6E4FF]/20 p-5"
            >
                <div
                    class="rounded-full border border-[#D6E4FF] bg-[#D6E4FF]/30 p-6 text-[#1E50A1]"
                >
                    <FileSearch
                        class="h-7 w-7"
                        :stroke-width="1.8"
                    />
                </div>
            </div>

            <div class="mt-4 max-w-xs space-y-1 text-center">
                <h3 class="font-bold text-[#505050]">
                    No appointment yet
                </h3>

                <p class="text-sm leading-5 text-[#606060]">
                    Try searching with a valid Appointment ID or check
                    your booking details.
                </p>
            </div>
        </div>
    </section>
</template>

<script>
import {
    UserRound,
    FileSearch,
} from 'lucide-vue-next';

export default {
    components: {
        UserRound,
        FileSearch,
    },

    props: {
        appointment: {
            type: Object,
            default: null,
        },
    },

    computed: {
        statusClass() {
            if (!this.appointment) return '';

            const status = this.appointment.status;

            if (status === 'waiting') {
                return 'bg-yellow-100 text-yellow-700';
            }

            if (['completed', 'done'].includes(status)) {
                return 'bg-green-100 text-green-700';
            }

            if (status === 'cancelled') {
                return 'bg-red-100 text-red-700';
            }

            return 'bg-gray-100 text-gray-600';
        },
    },

    methods: {
        formatTime(time) {
            if (!time) return '';

            const [hour, minute] = time.split(':');
            const h = parseInt(hour);

            const ampm = h >= 12 ? 'PM' : 'AM';
            const formattedHour = h % 12 || 12;

            return `${formattedHour}:${minute} ${ampm}`;
        },
    },
};
</script>