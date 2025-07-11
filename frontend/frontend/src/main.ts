import { createApp } from 'vue'
import './style.css'
import App from './App.vue'

// PrimeVue
import PrimeVue from 'primevue/config';
import Button from 'primevue/button';
import Sidebar from 'primevue/sidebar';
import Avatar from 'primevue/avatar';
import 'primevue/resources/themes/saga-blue/theme.css';
import 'primevue/resources/primevue.min.css';
import 'primeicons/primeicons.css';
import 'primeflex/primeflex.css';

// Vue Router (será criado em seguida)
import router from './router';

const app = createApp(App);
app.use(PrimeVue);
app.use(router);
app.component('Button', Button);
app.component('Sidebar', Sidebar);
app.component('Avatar', Avatar);
app.mount('#app');
