require('./bootstrap');

window.Vue = require('vue');
import vuetify from './vuetify'

import App from './components/App'
import router from './router/router'

const app = new Vue({
    vuetify,
    render: h => h(App),
    el: '#app',
    router
});

