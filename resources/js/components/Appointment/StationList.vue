<template>
    <div class="space-y-2">
        <h3 class="text-sm font-semibold text-[#99A1AF]">
            STATION
        </h3>

        <div
            class="min-h-[280px] rounded-lg border border-[#C0BBBB]/60 bg-white p-3"
        >
            <ul
                v-if="appointmentFound && stations?.length"
                class="space-y-2"
            >
                <li
                    v-for="station in stations"
                    :key="station.id"
                >
                    <button
                        type="button"
                        @click="$emit('select', station.id)"
                        class="flex w-full items-center justify-between rounded-lg 
                                p-3 text-left text-xs transition hover:bg-[#EEF4FF] 
                                border border-[#C0BBBB]/60 "
                        :class="selectedStation === station.id ? 'bg-[#EEF4FF]' : 'bg-white'"
                    >
                        <span class="flex min-w-0 items-center gap-3 "
                                    >
                            <span
                                class="rounded-full border p-1"
                                :class="
                                    selectedStation === station.id
                                        ? 'border-[#1E50A1]/30 bg-[#1E50A1]'
                                        : 'border-[#C0BBBB]/60 bg-[#C0BBBB]/10'
                                "
                            >
                                <component
                                    :is="getIconComponent(station.icon)"
                                    class="h-5 w-5 shrink-0 "
                                    :class="selectedStation === station.id ? 'text-white'  : 'text-[#1E50A1]' "
                                    :stroke-width="1.8"
                                />
                            </span>
                            
                            <span class="truncate font-semibold text-header uppercase">{{ station.name }}</span>

                        </span>

                        <span 
                            class="flex justify-center items-center rounded-full w-5 h-5"
                            :class="selectedStation === station.id ? 
                            'bg-[#1E50A1]': 'bg-white border border-[C0BBBB]/60'">
                            <span class="  rounded-full w-3 h-3"
                                  :class="selectedStation === station.id ? 
                                  'bg-white': ''" ></span>
                        </span>
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
import * as Icons from '@heroicons/vue/24/outline';
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

        appointmentFound: {
            type: Boolean,
            default: false,
        },

        selectedStation: {
            type: [Number, String],
            default: null,
        },
    },

        methods: {
        getIconComponent(iconName) {
            if (!iconName) {
                return Icons.QuestionMarkCircleIcon;
            }

            const nameWithoutPrefix = iconName.replace(
                /^heroicon-[csom]-/,
                ""
            );

            const pascalCase =
                nameWithoutPrefix
                    .split("-")
                    .map(
                        (word) =>
                            word.charAt(0).toUpperCase() + word.slice(1)
                    )
                    .join("") + "Icon";

            return (
                Icons[pascalCase] ||
                Icons.QuestionMarkCircleIcon
            );
        },
    },

    emits: ['select'],
};
</script>