import './bootstrap';
import { createApp } from 'vue';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faClock, faCircleCheck, faChevronRight, faChevronLeft } from '@fortawesome/free-solid-svg-icons';

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import router from './router';

import Welcome from './components/example.vue';
import GoBack from './components/backButton.vue';
import Header from './components/header.vue';
import ViewStation from './components/view-station.vue';
import ViewTransaction from './components/view-transaction.vue';
import GetInput from './components/get-input.vue';

const app = createApp({});

library.add(faClock, faCircleCheck,faChevronRight,faChevronLeft);

app.component('font-awesome-icon', FontAwesomeIcon);
app.component('welcome-component', Welcome)
app.component('kiosk-header', Header)
app.component('view-station', ViewStation)
app.component('view-transaction', ViewTransaction)
app.component('go-back', GoBack)
app.component('get-input', GetInput)


app.use(router).mount('#app');

