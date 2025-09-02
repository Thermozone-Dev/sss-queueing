<template>
    <div class="h-full text-white text-sm">
        <div class="bg-white flex items-center px-2 " style="height:15%">
            <p class="font-black text-black" style="font-size: 2.2rem">Now Serving</p>
        </div>
        <div  class="grid grid-rows-[1fr_1fr_1fr] place-content-stretch" style="height:85%">
            <div v-for="(nowserving) in now_serving"
                :key="nowserving.id" class="min-h-full text-white px-3 py-4 flex items-center justify-between"
                :style="{ backgroundColor: nowserving.bg_color }"
            >
                <div>
                    <p class="text-md font-semibold uppercase tracking-wide">{{nowserving.stations_name}} - {{nowserving.name}}</p>
                    <div class="flex items-baseline gap-3">
                    <span class="font-extrabold text-[2.8rem] leading-none ">{{nowserving.queue_number}} </span>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-md font-semibold uppercase tracking-wide">Window</p>
                    <span class="font-extrabold text-[2.8rem] leading-none">{{nowserving.station_code}}</span>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import {route} from 'ziggy-js';
    import axios from 'axios';
    export default {
        data() {
            return {
                now_serving: [],
                pollInterval: null,
            };
        },
        methods: {
            fetchTransactions() {
                axios.get(route('queues-get'))
                    .then(response => {
                        this.now_serving = response.data.data;
                    })
                    .catch(error => {
                        console.error("There was an error fetching the stations:", error.message);
                    });
            },

            startPolling() {
                this.fetchTransactions(); // fetch immediately
                this.pollInterval = setInterval(this.fetchTransactions, 15000); // every 5 sec
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
