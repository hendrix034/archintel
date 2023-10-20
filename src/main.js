import { createApp } from 'vue';
import App from './App.vue';
import axios from 'axios';
import 'primeicons/primeicons.css';
import 'primevue/resources/primevue.min.css';
import 'primeicons/primeicons.css';
import 'primevue/resources/themes/lara-light-indigo/theme.css';
import './assets/app.css';
import router from './router';
import Button from 'primevue/button';
import ToastService from 'primevue/toastservice';
import Toast from 'primevue/toast';
import PrimeVue from 'primevue/config';
import Avatar from 'primevue/avatar';
import MegaMenu from 'primevue/megamenu';
import Menu from 'primevue/menu';
import Menubar from 'primevue/menubar';
import InputText from 'primevue/inputtext';

const axiosInstance = axios.create({
    baseURL: 'http://localhost/archintel-backend/',
    // You can add other default configuration options here if needed
    headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
  });

const app = createApp(App);

// Axios setup
app.config.globalProperties.$axios = axiosInstance;

app.component('InputText', InputText);
app.component('Avatar', Avatar);
app.component('Toast', Toast);
app.component('Button', Button);
app.component('MegaMenu', MegaMenu);
app.component('Menu', Menu);
app.component('Menubar', Menubar);

app.use(router);
app.use(PrimeVue);
app.use(ToastService);
app.mount('#app');
