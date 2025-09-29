<template>
    <div class="bg-white min-h-full text-black col-span-1 grid place-content-stretch px-1">
        <div class="overflow-x-auto shadow-md sm:rounded-sm min-h-full max-h-full min-w-full text-white text-sm">
            <div v-for="station in queues" :key="station.id" class="border rounded-xl text-white station grid grid-rows-[10%_1fr] mb-1">
                <div class="text-center text-2xl font-bold  ">
                    <span>
                        {{station.station}} :
                    </span>
                    <span v-if="station.processing" class="text-xl" :style="{ color: theme.primary}">
                        {{ station.processing.queue_number }} - {{ station.processing.name}}
                    </span>
                    <span v-else class="text-gray-500 text-xl">
                        (Idle)
                    </span>
                </div>

                <div class=" rounded py-2 px-4 ">
                    <!-- Currently serving -->
                    <div v-if="station.queues.length > 0" class="flex justify-evenly gap-2 mt-2 text-lg">
                        <div v-for="(next, outerIndex) in station.queues" :key="outerIndex" class="flex flex-col gap-3 pr-2">
                            <div v-for="(queue, innerIndex) in next" :key="innerIndex" class="rounded text-center font-bold">
                                <span>
                                    {{ queue.queue_number }} - {{ queue.name }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div v-else class="rounded text-center text-md font-bold text-center text-gray-400">
                        <em>Nothing follows</em>
                    </div>
                </div>
            </div>

            <!-- <table class="w-full text-sm text-left rtl:text-right text-black">
                <thead>
                    <tr class="bg-white text-gray-700" style="font-size: 0.8rem">
                    <th v-for="station in queues" :key="station.id"
                        class="px-1 py-3 text-left font-semibold uppercase tracking-wider">
                        {{ station.station }}
                    </th>
                    </tr>
                </thead>

                <tbody v-if="queues.length > 0">
                    <tr v-for="i in maxQueues" :key="i" class="odd:bg-gray-200 even:bg-white">
                    <td v-for="station in queues" :key="station.id" class="px-2 py-0.5">
                        <template v-if="station.queues && station.queues[i - 1]">
                            <p class="uppercase font-medium text-[0.5rem]">{{ station.queues[i - 1].transaction_name }} - {{ station.queues[i - 1].name }}</p>
                            <p class="uppercase font-black text-[1rem]" >{{ station.queues[i - 1].queue_number }}</p>
                        </template>
                        <template v-else>
                        -
                        </template>
                    </td>
                    </tr>
                </tbody>

                <tbody v-else>
                    <tr>
                    <td :colspan="queues.length || 1" class="text-center py-4 text-gray-500">
                        Loading queues...
                    </td>
                    </tr>
                </tbody>
            </table> -->

        </div>
    </div>
</template>
<script>
    import {route} from 'ziggy-js';
    import axios from 'axios';
    export default {
        data() {
            return {
                theme: window.appTheme || {},
                queues: [],
                pollInterval: null,
            };
        },
        methods: {
            fetchTransactions() {
                axios.get(route('queues-next'))
                    .then(response => {
                        this.queues = response.data.data;
                    })
                    .catch(error => {
                        console.error("There was an error fetching the stations:", error.message);
                    });
            },

            startPolling() {
                this.fetchTransactions(); // fetch immediately
                this.pollInterval = setInterval(this.fetchTransactions, 5000); // every 5 sec
            },
            stopPolling() {
                if (this.pollInterval) clearInterval(this.pollInterval);
            },
        },

        mounted() {
            this.startPolling();
        },
        beforeUnmount() {
            this.stopPolling();
        },

    };
</script>
<style>
    .station:nth-child(odd) {
    background-color: #14B8A6; /* teal */
    }
    .station:nth-child(even) {
    background-color: #4ADE80; /* green */
    }
</style>
