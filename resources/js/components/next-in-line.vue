<template>
    <div class="px-2 py-2">

      <div class="lanes-container grid grid-cols-3 gap-2">
        <LaneSection
          v-for="(queues, lane) in groupedQueues"
          :key="lane"
          :laneName="lane"
          :queues="queues"
        />
      </div>
    </div>
  </template>
<script>
    import LaneSection from './QueueSection.vue'
    import { route } from 'ziggy-js';
    import axios from 'axios';

    export default {
        components: {
            LaneSection
        },
        data() {
            return {
                theme: window.appTheme || {},
                allQueues: [], // start empty (no more static data)
                pollInterval: null,
            };
        },
        computed: {
            groupedQueues() {
            return this.allQueues.reduce((groups, queue) => {
                if (!groups[queue.lane]) {
                groups[queue.lane] = []
                }
                groups[queue.lane].push(queue)
                return groups
            }, {})
            }
        },

        methods: {
            fetchTransactions() {
                axios.get(route('queues-next'))
                    .then(response => {
                        this.allQueues = response.data.data;
                    })
                    .catch(error => {
                        console.error("Error fetching queues:", error.message);
                    });
            },

            startPolling() {
                this.fetchTransactions();
                this.pollInterval = setInterval(this.fetchTransactions, 5000);
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
    .main-title {
        font-size: 24px;
        margin-bottom: 20px;
    }

</style>
