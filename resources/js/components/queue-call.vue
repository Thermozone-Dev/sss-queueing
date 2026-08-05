<template>
     <div  v-if="showModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 ">
        <div class="text-center shadow rounded-2xl p-8 relative" :style="{backgroundColor: theme.primary}">
            <!-- <audio ref="modalSound" :src="soundSrc" autoplay loop></audio> -->
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
                            this.speakQueue();
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
            },
            speakQueue() {
                const msg = `Queue number ${this.queue_details.queue_number}, ${this.queue_details.name},  please proceed to ${this.queue_details.transaction}`;

                const utterance = new SpeechSynthesisUtterance(msg);

                // Make it slower (normal = 1)
                utterance.rate = 0.6;   // 0.5 - 2 (lower = slower)
                utterance.pitch = 1.2;  // Slightly higher = more feminine

                function setVoice() {
                    const voices = window.speechSynthesis.getVoices();

                    // Try to find a female English voice
                    const femaleVoice = voices.find(voice =>
                        voice.name.toLowerCase().includes('female') ||
                        voice.name.toLowerCase().includes('woman') ||
                        voice.name.toLowerCase().includes('zira') ||     // Windows female
                        voice.name.toLowerCase().includes('samantha') || // Mac female
                        voice.name.toLowerCase().includes('google us english')
                    );

                    if (femaleVoice) {
                        utterance.voice = femaleVoice;
                    }

                    window.speechSynthesis.speak(utterance);
                }

                // Fix for browsers that load voices asynchronously
                if (speechSynthesis.getVoices().length === 0) {
                    speechSynthesis.onvoiceschanged = setVoice;
                } else {
                    setVoice();
                }
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
