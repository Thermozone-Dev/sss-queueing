<template>
    <kiosk-header :header="header" :description="description" />
    <go-back/>
    <div class="grid grid-cols-1 gap-6 mt-6 text-white items-center">
        <div v-for="transaction in transactions" :key="transaction.id"  class="cursor-pointer px-8 py-5 rounded-xl" :style="{backgroundColor: theme.primary}">
            <router-link :to="{ name: 'view-transaction', params: { id: transaction.id }}" >
                <h1 class="capitalize font-bold text-3xl flex items-center justify-between">{{transaction.name}} <font-awesome-icon :icon="['fas', 'chevron-right']" class="h-4 w-4" /></h1>
           </router-link>
        </div>
    </div>
    <div class="text-center justify-center mt-5">
        <p class="text-xl text-black mb-1 font-bold">Please bring all required documents</p>
        <p class="text-black flex items-center justify-center">
            <font-awesome-icon :icon="['fas', 'circle-check']" class="w-4 h-4 mr-2" />
            Check requirements list posted on each department
        </p>
        <span class="my-4">
            <p class="text-gray-600 text-center py-5">For assistance, please approach our staff</p>
        </span>
    </div>

</template>

<script>
    import {route} from 'ziggy-js';
    import KioskHeader from './header.vue';
    import axios from 'axios';
    export default {
        components: {
            KioskHeader
        },
        props: {
            type: [String, Number],
            id: 'id' || null,
        },
        data() {
            return {
                theme: window.appTheme || {},
                header: 'Welcome',
                description: 'Pumili ng serbisyo para makuha ang iyong numero',
                transactions: [],
            };
        },

        mounted() {
            axios.get(route('get-stations-transaction', { id: this.id }))
                .then(response => {
                    this.header = response.data.station_name;
                    this.transactions = response.data.data;
                })
                .catch(error => {
                    console.error("There was an error fetching the stations:", error.message);
                });
        },
    };

</script>
