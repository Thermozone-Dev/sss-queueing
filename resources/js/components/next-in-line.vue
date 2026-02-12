<template>
    <div class="px-2 py-2">

      <div class="lanes-container">
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
                allQueues: [
                    { name: "Test Senior", queueNumber: "S-001", station: "MP", lane: "Appointment" },
                    { name: "Test Senior", queueNumber: "S-001", station: "MP", lane: "Appointment" },
                    { name: "Test Senior", queueNumber: "S-001", station: "MP", lane: "Appointment" },
                    { name: "Test Senior", queueNumber: "S-001", station: "MP", lane: "Appointment" },
                    { name: "Test Senior", queueNumber: "S-001", station: "MP", lane: "Appointment" },
                    { name: "Test Regular", queueNumber: "R-010", station: "ST1", lane: "Regular" },
                    { name: "Test Regular", queueNumber: "R-010", station: "ST1", lane: "Regular" },
                    { name: "Test Regular", queueNumber: "R-010", station: "ST1", lane: "Regular" },
                    { name: "Test Regular", queueNumber: "R-010", station: "ST1", lane: "Regular" },
                    { name: "Test Regular", queueNumber: "R-010", station: "ST1", lane: "Regular" },
                    { name: "Test Priority", queueNumber: "P-005", station: "ST2", lane: "Senior" },
                    { name: "Another Senior", queueNumber: "S-002", station: "MP", lane: "Senior" },
                    { name: "Another Senior", queueNumber: "S-002", station: "MP", lane: "Senior" },
                    { name: "Another Senior", queueNumber: "S-002", station: "MP", lane: "Senior" },
                    { name: "Another Senior", queueNumber: "S-002", station: "MP", lane: "Senior" }

                ],
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

        // methods: {
        //     fetchTransactions() {
        //         axios.get(route('queues-next'))
        //             .then(response => {
        //                 // You group this in backend
        //                 this.queues = response.data.data;
        //             })
        //             .catch(error => {
        //                 console.error("Error fetching queues:", error.message);
        //             });
        //     },

        //     startPolling() {
        //         this.fetchTransactions();
        //         this.pollInterval = setInterval(this.fetchTransactions, 5000);
        //     },

        //     stopPolling() {
        //         if (this.pollInterval) clearInterval(this.pollInterval);
        //     },
        // },

        // mounted() {
        //     this.startPolling();
        // },

        // beforeUnmount() {
        //     this.stopPolling();
        // },
    };

</script>
<style>
    .main-title {
        font-size: 24px;
        margin-bottom: 20px;
    }

    .lanes-container {
        display: grid;
        grid-template-rows: repeat(3, 1fr);
        gap: 20px;
    }
</style>
