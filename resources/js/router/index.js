import { createRouter, createWebHistory } from 'vue-router';

import Welcome from '../components/example.vue';
import ViewStation from '../components/view-station.vue';
import ViewTransaction from '../components/view-transaction.vue';
import GetInput from '../components/get-input.vue';
import ConfirmService from '../components/confirm-service.vue';
import QueueInfo from '../components/queue-complete.vue';
import NowServing  from '../components/now-serving.vue';


const router = createRouter({
    history: createWebHistory('/'),
    routes: [
        { path: '/queue-kiosk', component: Welcome, name: 'home' },
        { path: '/view-station/:id', component: ViewStation, name: 'view-station', props: true },
        { path: '/view-transaction/:id', component: ViewTransaction, name: 'view-transaction', props: true },
        { path: '/queue-input/:id', component: GetInput, name: 'get-queue', props: true },
        { path: '/confirm-service/:id', component: ConfirmService, name: 'confirm-service', props: true },
        { path: '/queue-complete/:transaction_name/:queue_number', component: QueueInfo, name: 'complete-queue', props:true},
        { path: '/queue-board', component: NowServing, name: 'now-serving', props:true},


        // Define your routes here
    ],
});

export default router;
