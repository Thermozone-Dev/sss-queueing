import './bootstrap';
import { createApp } from 'vue';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faClock, faCircleCheck, faChevronRight, faChevronLeft, faUserClock } from '@fortawesome/free-solid-svg-icons';

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import router from './router';
import axios from "axios";


import Welcome from './components/example.vue';
import GoBack from './components/backButton.vue';
import Header from './components/header.vue';
import ViewStation from './components/view-station.vue';
import ViewTransaction from './components/view-transaction.vue';
import GetInput from './components/get-input.vue';
import ConfirmService from './components/confirm-service.vue';
import QueueInfo from './components/queue-complete.vue';
import NowServing from './components/now-serving.vue';
import NextInLine from './components/next-in-line.vue';
import QueueCall from './components/queue-call.vue';
import ProgressOverlay from './components/ProgressOverlay.vue';
import GetAppointment from './components/get-appointment.vue';
import QueueSection from './components/QueueSection.vue'

library.add(faClock, faCircleCheck,faChevronRight,faChevronLeft, faUserClock);

if (document.querySelector('#app')) {
    const app = createApp({
        mounted() {
            const progress = this.$refs.progressOverlay;

            axios.interceptors.request.use(
                (config) => {
                    progress.start();
                    return config;
                },
                (error) => {
                    progress.fail();
                    return Promise.reject(error);
                }
            );

            axios.interceptors.response.use(
                (response) => {
                    progress.finish();
                    return response;
                },
                (error) => {
                    progress.fail();
                    return Promise.reject(error);
                }
            );
        }
    });

    app.component('font-awesome-icon', FontAwesomeIcon);
    app.component('welcome-component', Welcome)
    app.component('kiosk-header', Header)
    app.component('view-station', ViewStation)
    app.component('view-transaction', ViewTransaction)
    app.component('go-back', GoBack)
    app.component('get-input', GetInput)
    app.component('confirm-service', ConfirmService)
    app.component('queue-complete', QueueInfo)
    app.component('progress-overlay', ProgressOverlay);
    app.component('get-appointment', GetAppointment);
    app.component('queue-section', QueueSection)

    app.use(router);
    app.mount('#app');
}

// SPA 2: Kiosk
if (document.querySelector('#app2')) {
    const app2 = createApp({});
    app2.component('now-serving', NowServing);
    app2.component('next-in-line', NextInLine);
    app2.component('queue-call', QueueCall);
    app2.mount('#app2');
}

