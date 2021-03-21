require('./bootstrap');

window.Vue = require('vue');
import vuetify from './vuetify'

import App from './components/App'
import router from './router/router'
import NProgress from "vue-nprogress";

Vue.use(NProgress);

const nprogress = new NProgress();

const app = new Vue({
    vuetify,
    nprogress,
    render: h => h(App),
    el: '#app',
    router
});

