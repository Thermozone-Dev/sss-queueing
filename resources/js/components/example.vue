<template>
    <kiosk-header :header="header" :description="description">
    </kiosk-header>
    <div  class="grid grid-cols-2 sm:grid-cols-2 xs:grid-cols-1 gap-6 mt-6 text-white items-center" >
        <div  v-for="station in stations" :key="station.id" class="sm:px-4 xs:px-2 px-4 rounded-lg text-center justify-center py-5"
                :style="{ backgroundColor: theme.primary, color: theme.secondary}"
            >
            <!-- <div
                :class="station.status === 0
                    ? 'disable opacity-50'
                    : 'enable hover:scale-105 transform transition duration-300 ease-in-out'"
            > -->

            <div
                v-if="station.status == 1"
                class="enable hover:scale-105 transform transition duration-300 ease-in-out"
            >
                <router-link :to="{ name: 'view-station', params: { id: station.id } }">
                    <component
                        :is="getIconComponent(station.icon)"
                        class="h-32 w-full mb-4"
                        :style="{ color: theme.secondary}"
                        />
                    <h1 class="uppercase font-bold text-xl">{{station.name}}</h1>
                    <h4 class="uppercase text-sm">{{station.description}}</h4>
                </router-link>
            </div>

            <div
                v-else
                class="disable"
            >
                <component
                    :is="getIconComponent(station.icon)"
                    class="h-32 w-full mb-4 opacity-50"
                    :style="{ color: theme.secondary}"
                    />
                <span class="text-xs font-medium me-2 px-2.5 py-0.5 rounded-full"
                    :style="{ backgroundColor: theme.warning, color: theme.secondary}"
                >
                    Offline
                </span>

                <h1 class="uppercase font-bold text-xl opacity-50">{{station.name}}</h1>
                <h4 class="uppercase text-sm opacity-50">{{station.description}}</h4>
            </div>
        </div>

    </div>
    <div class="text-center justify-center mt-5">
        <p class="text-xl text-black mb-1">Touch any button above to begin your transaction</p>
        <p class="text-gray-600">For assistance, please approach our staff</p>
        <span class="my-4 flex">
            <span class="mx-auto bg-gray-300 flex items-center rounded-lg px-5 py-1">
                <font-awesome-icon :icon="['fas', 'clock']" class="text-black-400 h-4 w-4 mr-5" />
                <span class="font-bold mr-2">Operating Hours: </span>
                8:00 AM - 5:00 PM Monday - Friday
            </span>
        </span>
    </div>
</template>

<script>
import axios  from "axios";
import * as Icons from '@heroicons/vue/24/solid';
import {route} from 'ziggy-js';

export default {
    props: {
        header: { type: String, default: 'Welcome' },
        description: { type: String, default: 'Please select a service' },
    },
    data() {
        return {
            theme: window.appTheme || {},
            stations: [],
        };
    },

    methods: {
        getIconComponent(iconName) {
            const nameWithoutPrefix = iconName.replace(/^heroicon-[csom]-/, '');

            const pascalCase = nameWithoutPrefix
                .split('-')
                .map(word => word.charAt(0).toUpperCase() + word.slice(1))
                .join('') + 'Icon';

            return Icons[pascalCase] || Icons['QuestionMarkCircleIcon'];
        }
    },
    mounted() {
        axios.get(route('get-stations'))
            .then(response => {
                this.stations = response.data.data;
            })
            .catch(error => {
                console.error("There was an error fetching the stations:", error.message);
            });
    },

    // Component options
};
</script>
