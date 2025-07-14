import { createApp } from 'vue'
import './style.css'
import App from './App.vue'

// PrimeVue
import PrimeVue from 'primevue/config';
import ConfirmationService from 'primevue/confirmationservice';
import 'primevue/resources/themes/saga-blue/theme.css';
import 'primevue/resources/primevue.min.css';
import 'primeicons/primeicons.css';
import 'primeflex/primeflex.css';

// PrimeVue Components
import Button from 'primevue/button';
import Sidebar from 'primevue/sidebar';
import Avatar from 'primevue/avatar';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Dialog from 'primevue/dialog';
import ConfirmDialog from 'primevue/confirmdialog';
import InputText from 'primevue/inputtext';
import InputNumber from 'primevue/inputnumber';
import Textarea from 'primevue/textarea';
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import Dropdown from 'primevue/dropdown';
import Calendar from 'primevue/calendar';
import { useConfirm } from 'primevue/useconfirm';
import { useToast } from 'primevue/usetoast';
import Toast from 'primevue/toast';
import ToastService from 'primevue/toastservice';

// Vue Router
import router from './router';

const app = createApp(App);
app.use(PrimeVue);
app.use(ToastService);
app.use(ConfirmationService);
app.use(router);

// Register components
app.component('Button', Button);
app.component('Sidebar', Sidebar);
app.component('Avatar', Avatar);
app.component('DataTable', DataTable);
app.component('Column', Column);
app.component('Dialog', Dialog);
app.component('ConfirmDialog', ConfirmDialog);
app.component('InputText', InputText);
app.component('InputNumber', InputNumber);
app.component('Textarea', Textarea);
app.component('TabView', TabView);
app.component('TabPanel', TabPanel);
app.component('Dropdown', Dropdown);
app.component('Calendar', Calendar);
app.component('Toast', Toast);

app.mount('#app');
