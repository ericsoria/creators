import { createApp } from 'vue';
import App from './operations/App.vue';
import { router } from './operations/router';
import '../css/app.css';

createApp(App).use(router).mount('#app');
