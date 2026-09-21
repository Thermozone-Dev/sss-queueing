<template>
    <section
        class="flex min-h-[440px] flex-col space-y-12 rounded-2xl border border-[#D6E4FF] bg-white px-5 py-5 md:px-6 xl:col-span-2"
    >
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="w-8"></div>

            <h2 class="text-center font-bold text-[#505050]">
                Appointment Details
            </h2>

            <div
                class="rounded-full border border-[#D6E4FF]/60 bg-[#D6E4FF]/20 p-2 text-[#1E50A1]"
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
            <div class="space-y-5 rounded-xl p-4">
                <div class="flex items-center justify-between text-xs">
                    <div class="flex items-center gap-2">
                        <div class="rounded-full bg-[#C0BBBB]/10  border border-[#C0BBBB]/20 p-1">
                            <Ticket class="text-header w-5 h-5"/>
                        </div>
                            <p class="text-paragraph font-semibold">
                                CODE : 
                            </p>
                    </div>
                    <p class="uppercase">
                        {{ appointment.code }}
                    </p>
                </div>

                <div class="flex items-center justify-between text-xs">
                    <div class="flex items-center gap-2">
                        <div class="rounded-full bg-[#C0BBBB]/10  border border-[#C0BBBB]/20 p-1">
                            <User class="text-header w-5 h-5"/>
                        </div>
                        <p class="text-paragraph font-semibold">
                            Name :
                        </p>

                    </div>
                    
                    <p class="capitalize font-semibold">
                        {{ appointment.name }}
                    </p>
                </div>

                <div class="flex items-center justify-between text-xs">
                    <div class="flex items-center gap-2">
                        <div class="rounded-full bg-[#C0BBBB]/10  border border-[#C0BBBB]/20 p-1">
                            <CalendarDays class="text-header w-5 h-5"/>
                        </div>
                        <p class="text-paragraph font-semibold">
                            Date :
                        </p>

                    </div>
                    
                    <p class="capitalize font-semibold">
                        {{ appointment.date }}
                    </p>
                </div>

                <div class="flex items-center justify-between text-xs">
                    <div class="flex items-center gap-2">
                        <div class="rounded-full bg-[#C0BBBB]/10  border border-[#C0BBBB]/20 p-1">
                            <Clock2 class="text-header w-5 h-5"/>
                        </div>
                        <p class="text-paragraph font-semibold">
                            Time :
                        </p>

                    </div>
                    
                    <p class="capitalize font-semibold">
                            {{ formatTime(appointment.time) }}
                    </p>
                </div>
            </div>

            <!-- Status -->
           <div  class="flex items-center justify-between gap-2 
                        mx-auto w-[90%] rounded-xl border border-[#D6E4FF] px-3 py-3 bg-[#C0BBBB]/5">
            <!-- Status Label -->
            <span class="flex items-center gap-2">
                    <span
                        class="flex justify-center items-center inline-block h-5 w-5 rounded-full border border-[#C0BBBB]/60 bg-white"
                    >
                     <span class=" h-3 w-3 rounded-full "
                        :class="statusClass"></span>
                </span>

                    <span class="text-sm font-semibold text-[#606060]">
                        Status
                    </span>
                </span>

                <!-- Status Badge -->
               <span
                    class="inline-flex items-center gap-2 rounded-full px-4 py-1 text-xs font-bold capitalize "
                    :class="statusClass"
                >
                    <span class="h-2 w-2 shrink-0 rounded-full "
                    :class="statusDotClass"></span>

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
    Ticket,
    User,
    CalendarDays,
    Clock2
} from 'lucide-vue-next';

export default {
    components: {
        UserRound,
        FileSearch,
        Ticket, 
        User,
        CalendarDays,
        Clock2
    },

    props: {
        appointment: {
            type: Object,
            default: null,
        },
    },

        computed: {
            statusStyles() {
                const status = this.appointment?.status;

                return {
                    waiting: {
                        badge: 'bg-[#FABC00] text-header',
                        dot: 'bg-header',
                    },
                    completed: {
                        badge: 'bg-green-700 text-white',
                        dot: 'bg-white',
                    },
                    serving: {
                        badge: 'bg-green-700 text-white',
                        dot: 'bg-white',
                    },
                    cancelled: {
                        badge: 'bg-red-700 text-white',
                        dot: 'bg-white',
                    },
                }[status] ?? {
                    badge: 'bg-gray-700 text-white',
                    dot: 'bg-gray-500',
                };
            },

            statusClass() {
                return this.statusStyles.badge;
            },

            statusDotClass() {
                return this.statusStyles.dot;
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