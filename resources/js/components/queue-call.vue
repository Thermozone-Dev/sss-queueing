<template>
     <div  v-if="showModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 ">
        <div class="text-center shadow rounded-2xl p-8 relative" :style="{backgroundColor: theme.primary}">
            <audio ref="modalSound" :src="soundSrc" autoplay loop></audio>
            <span class="text-white font-black" style="font-size: 8rem">{{queue_details.queue_number}}</span>
            <p class="font-bold captitalize text-white" style="font-size: 4rem">{{queue_details.name}}</p>
            <p class="font-bold text-4xl captitalize">{{queue_details.transaction}}</p>

        </div>
    </div>
</template>
<script>
    import {route} from 'ziggy-js';
    import axios from 'axios';
    export default {
        data() {
            return {
                theme: window.appTheme,
                showModal: false,
                queue_details: [],
                pollInterval: null,
                soundSrc: '/images/default_front_end/call_number_sound.wav',
            };
        },
        methods: {
            fetchTransactions() {
                axios.get(route('queues-call-next'))
                    .then(response => {
                        if(response.data.status !== 'empty'){
                            this.queue_details = response.data.data;
                            this.displayModal()
                        }
                    })
                    .catch(error => {
                        console.error("There was an error fetching the stations:", error.message);
                    });
            },
            startPolling() {
                this.showModal = false;
                this.pollInterval = setInterval(this.fetchTransactions, 8000); // every 8 sec
            },
            stopPolling() {
                if (this.pollInterval) clearInterval(this.pollInterval);
            },
            displayModal(){
                this.stopPolling();
                this.showModal = true;
                setTimeout(() => {
                    this.showModal = false;
                    this.startPolling()
                }, 5000);
            }
        },

        mounted() {
            this.startPolling();
        },
        beforeUnmount() {
            this.stopPolling();
        },

    };
</script>
