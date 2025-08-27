import { createRouter, createWebHistory } from 'vue-router';

import Welcome from '../components/example.vue';
import ViewStation from '../components/view-station.vue';
import ViewTransaction from '../components/view-transaction.vue';
import GetInput from '../components/get-input.vue';


const router = createRouter({
    history: createWebHistory('/queue-kiosk'),
    routes: [
        { path: '/', component: Welcome, name: 'home' },
        { path: '/view-station/:id', component: ViewStation, name: 'view-station', props: true },
        { path: '/view-transaction/:id', component: ViewTransaction, name: 'view-transaction', props: true },
        { path: '/queue-input/:id', component: GetInput, name: 'get-queue', props: true },

        // Define your routes here
    ],
});

export default router;
