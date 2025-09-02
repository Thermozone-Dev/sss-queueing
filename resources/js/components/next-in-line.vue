<template>
    <div class="bg-white min-h-full text-black col-span-1 grid place-content-stretch px-1">
        <div class="overflow-x-auto shadow-md sm:rounded-sm min-h-full max-h-full min-w-full text-white text-sm">
            <table class="w-full text-sm text-left rtl:text-right text-black">
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
            </table>

        </div>
    </div>
</template>
<script>
    import {route} from 'ziggy-js';
    import axios from 'axios';
    export default {
        data() {
            return {
                queues: [],
                pollInterval: null,
            };
        },
        computed: {
            maxQueues() {
                if (!this.queues || this.queues.length === 0) return 0;
                return Math.max(...this.queues.map(q => (q.queues ? q.queues.length : 0)));
            },
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
