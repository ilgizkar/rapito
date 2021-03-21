import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter);

import App from "../components/App";
import GroupSearch from "../components/GroupSearch";
import Enlarge from "../components/Enlarge";

const routes = [
    { path: '/dashboard', component: App},
    { path: '/dashboard/poisk_grup_vk', component: GroupSearch},
    { path: '/dashboard/nakrutka_vk', component: Enlarge},
];


const router = new VueRouter({
    routes, // сокращённая запись для `routes: routes`
    hashbang: false,
    mode: 'history'
});

// router.beforeResolve((to, from, next) => {
//     if (to.name) {
//         NProgress.start()
//     }
//     next()
// });

// router.afterEach((to, from) => {
//     NProgress.done()
// });


export default router
