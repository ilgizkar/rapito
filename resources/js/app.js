require('./bootstrap');

window.Vue = require('vue');
import vuetify from './vuetify'

import App from './components/App'
import Vuex from 'vuex'
import router from './router/router'
import NProgress from "vue-nprogress";

Vue.use(NProgress);
Vue.use(Vuex);

const store = new Vuex.Store({
    state: {
        count: 0,
        user: null,
        balance: 0,
    },
    mutations: {
        increment (state) {
            state.count++
        },
        addUser (state, id = null) {
            if(id) {
                VK.Api.call('users.get', {user_ids: id, v:"5.101", fields:['photo_id', 'verified', 'sex', 'bdate', 'city', 'country', 'home_town', 'has_photo', 'photo_50', 'photo_100', 'photo_200_orig', 'photo_200', 'photo_400_orig', 'photo_max', 'photo_max_orig', 'online', 'domain', 'has_mobile', 'contacts', 'site', 'education', 'universities', 'schools', 'status', 'last_seen', 'followers_count', 'common_count', 'occupation', 'nickname', 'relatives', 'relation', 'personal', 'connections', 'exports', 'activities', 'interests', 'music', 'movies', 'tv', 'books', 'games', 'about', 'quotes', 'can_post', 'can_see_all_posts', 'can_see_audio', 'can_write_private_message', 'can_send_friend_request', 'is_favorite', 'is_hidden_from_feed', 'timezone', 'screen_name', 'maiden_name', 'crop_photo', 'is_friend', 'friend_status', 'career', 'military', 'blacklisted', 'blacklisted_by_me', 'can_be_invited_group']}, (r) => {
                    console.log(r.response[0]);
                    let user = r.response[0];
                    if(user !== undefined) {
                        state.user = user;
                    }
                });
            } else {
                state.user = null;
            }

        },
        setBalancePlus(state, int) {
            state.balance += int
        },
        setBalanceMinus(state, int) {
            state.balance -= int
        }
    }
});

const nprogress = new NProgress();

const app = new Vue({
    vuetify,
    store,
    nprogress,
    render: h => h(App),
    el: '#app',
    router
});

