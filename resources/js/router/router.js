import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter);

import App from "../components/App";
import GroupSearch from "../components/GroupSearch";

const routes = [
    { path: '/dashboard', component: App},
    { path: '/dashboard/group_search', component: GroupSearch},
];


const router = new VueRouter({
    routes, // сокращённая запись для `routes: routes`
    hashbang: false,
    mode: 'history'
});

router.beforeResolve((to, from, next) => {
    if (to.name) {
        NProgress.start()
    }
    next()
});

router.afterEach((to, from) => {
    NProgress.done()
});


export default router
