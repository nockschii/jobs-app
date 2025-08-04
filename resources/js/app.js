import './bootstrap';

import { createApp } from 'vue'
import App from './vueJs/App.vue'
import AddJob from './vueJs/components/pages/AddJob.vue'

const app = createApp()
app.component('app', App)
app.component('add-job', AddJob)
app.mount('#app');
