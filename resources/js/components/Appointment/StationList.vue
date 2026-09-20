<template>
    <div class="space-y-2">
        <h3 class="text-sm font-semibold text-[#99A1AF]">
            STATION
        </h3>

        <div
            class="min-h-[280px] rounded-lg border border-[#C0BBBB]/60 bg-white p-3"
        >
            <ul
                v-if="stations?.length"
                class="space-y-2"
            >
                <li
                    v-for="station in stations"
                    :key="station.id"
                >
                    <button
                        type="button"
                        @click="$emit('select', station.id)"
                        class="flex w-full items-center justify-between rounded-lg border border-transparent p-3 text-left text-sm transition hover:bg-[#EEF4FF]"
                        :class="
                            selectedStation === station.id
                                ? 'border-[#D6E4FF] bg-[#D6E4FF]/60 font-semibold text-[#1E50A1]'
                                : 'text-[#505050]'
                        "
                    >
                        <span>{{ station.name }}</span>

                        <ChevronRight
                            class="h-[18px] w-[18px]"
                            :stroke-width="2"
                        />
                    </button>
                </li>
            </ul>

            <div
                v-else
                class="flex min-h-[250px] flex-col items-center justify-center gap-4 px-3"
            >
                <div
                    class="flex size-20 items-center justify-center rounded-full border border-[#D6E4FF] bg-[#D6E4FF]/20"
                >
                    <Archive
                        class="h-8 w-8 text-[#1E50A1]"
                        :stroke-width="1.5"
                    />
                </div>

                <div class="max-w-48 space-y-1 text-center">
                    <h3 class="text-sm font-semibold text-[#505050]">
                        No stations available
                    </h3>

                    <p class="text-sm leading-5 text-[#606060]">
                        Please contact front desk for assistance.
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {
    ChevronRight,
    Archive,
} from 'lucide-vue-next';

export default {
    components: {
        ChevronRight,
        Archive,
    },

    props: {
        stations: {
            type: Array,
            default: () => [],
        },

        selectedStation: {
            type: [Number, String],
            default: null,
        },
    },

    emits: ['select'],
};
</script>