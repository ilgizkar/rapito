<template>
    <v-app id="inspire">
        <v-navigation-drawer
            v-model="drawer"
            :clipped="$vuetify.breakpoint.lgAndUp"
            app
        >
            <v-list dense>
                <template v-for="item in items">
                    <v-row
                        v-if="item.heading"
                        :key="item.heading"
                        align="center"
                    >
                        <v-col cols="6">
                            <v-subheader v-if="item.heading">
                                {{ item.heading }}
                            </v-subheader>
                        </v-col>
                        <v-col
                            cols="6"
                            class="text-center"
                        >
                            <a
                                href="#!"
                                class="body-2 black--text"
                            >EDIT</a>
                        </v-col>
                    </v-row>
                    <v-list-group
                        v-else-if="item.children"
                        :key="item.text"
                        v-model="item.model"
                        :prepend-icon="item.model ? item.icon : item['icon-alt']"
                        append-icon=""
                    >
                        <template v-slot:activator>
                            <v-list-item-content>
                                <v-list-item-title>
                                    {{ item.text }}
                                </v-list-item-title>
                            </v-list-item-content>
                        </template>
                        <v-list-item
                            v-for="(child, i) in item.children"
                            :key="i"
                            link
                        >
                            <v-list-item-action v-if="child.icon">
                                <v-icon>{{ child.icon }}</v-icon>
                            </v-list-item-action>
                            <v-list-item-content>
                                <v-list-item-title>
                                    {{ child.text }}
                                </v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list-group>
                    <v-list-item
                        v-else
                        :key="item.text"
                        link
                    >
                        <v-list-item-action>
                            <v-icon>{{ item.icon }}</v-icon>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>
                                <router-link :to="item.to"> {{ item.text }}</router-link>
                            </v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </template>
            </v-list>
        </v-navigation-drawer>

        <v-app-bar
            :clipped-left="$vuetify.breakpoint.lgAndUp"
            app
            dense
            color="#5181b8"
            dark
        >
            <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
            <v-toolbar-title
                style="width: 300px; font-size: 1.6rem"
                class="ml-0 pl-4"
            >
                <span class="hidden-sm-and-down font-weight-black">
                    R A P I T O
                </span>
            </v-toolbar-title>
            <v-text-field
                flat
                dense
                clearable
                :width="100"
                solo-inverted
                hide-details
                prepend-inner-icon="mdi-magnify"
                label="Поиск по сайту"
                class="hidden-sm-and-down shrink"
            ></v-text-field>
            <v-spacer></v-spacer>
            <v-btn icon class="mr-2">
                <v-icon>mdi-apps</v-icon>
            </v-btn>
            <v-btn title="Уведомления" icon class="mr-2">
                <v-icon>mdi-bell</v-icon>
            </v-btn>
            <v-btn @click="vkAuth()" title="Войти" v-if="!user" icon class="mr-2">
                <v-icon :size="30">mdi-account</v-icon>
            </v-btn>
            <v-menu v-else offset-y>
                <template v-slot:activator="{ on }">
                    <v-btn
                        :retain-focus-on-click="true"
                        class="mr-2"
                        icon
                        large
                        v-on="on"
                        :title="user.first_name + ' ' + user.last_name"
                    >
                        <v-avatar
                            size="37px"
                            item
                        >
                            <v-img
                                :src="user.photo_200 ? user.photo_200 : 'https://vk.com/images/camera_200.png?ava=1'"
                            ></v-img></v-avatar>
                    </v-btn>
                </template>
                <v-list>
                    <v-list-item
                        v-for="(userItem, index) in userItems"
                        :key="index"
                        @click="userItemClick(userItem.click)"
                    >
                        <v-list-item-title>{{ userItem.title }}</v-list-item-title>
                    </v-list-item>
                </v-list>
            </v-menu>
        </v-app-bar>
        <v-content>
            <router-view></router-view>
        </v-content>
        <!-- VK Widget -->
        <div id="vk_community_messages"></div>
    </v-app>
</template>

<script>
    VK.Widgets.CommunityMessages("vk_community_messages", 85750218, {tooltipButtonText: "Есть вопрос?"});

    export default {
        data: () => ({
            dialog: false,
            drawer: null,
            vk_session: null,
            user: null,
            userItems: [
                { title: 'Профиль', click: 'logoutUser' },
                { title: 'Баланс', click: 'logoutUser' },
                { title: 'Задания', click: 'logoutUser' },
                { title: 'Выйти', click: 'logoutUser' },
            ],
            items: [
                { icon: 'mdi-contacts', text: 'Поиск по группам' , to: '/dashboard/group_search'},
                { icon: 'mdi-history', text: 'Frequently contacted' , to: '/group_search'},
                { icon: 'mdi-content-copy', text: 'Duplicates' , to: '/group_search'},
                {
                    icon: 'mdi-chevron-up',
                    'icon-alt': 'mdi-chevron-down',
                    text: 'Labels',
                    to: '/group_search',
                    model: true,
                    children: [
                        { icon: 'mdi-plus', text: 'Create label' , to: '/group_search'},
                    ],
                },
                {
                    icon: 'mdi-chevron-up',
                    'icon-alt': 'mdi-chevron-down',
                    text: 'More',
                    to: '/group_search',
                    model: false,
                    children: [
                        { text: 'Import' , to: '/group_search'},
                        { text: 'Export' , to: '/group_search'},
                        { text: 'Print' , to: '/group_search'},
                        { text: 'Undo changes' , to: '/group_search'},
                        { text: 'Other contacts' , to: '/group_search'},
                    ],
                },
                { icon: 'mdi-cog', text: 'Settings' , to: '/group_search'},
                { icon: 'mdi-message', text: 'Send feedback' , to: '/group_search'},
                { icon: 'mdi-help-circle', text: 'Help' , to: '/group_search'},
                { icon: 'mdi-cellphone-link', text: 'App downloads' , to: '/group_search'},
                { icon: 'mdi-keyboard', text: 'Go to the old version' , to: '/group_search'},
            ],
        }),
        created() {
            // axios.get('/getUser')
            //     .then(res => res.data.avatar ? this.avatar = res.data.avatar : '')
        },
        mounted() {
            VK.init({
                apiId: 	7491900, onlyWidgets: false
            });
            this.vk_session = VK.Auth.getSession();
            if(this.vk_session) {
                this.getUser(this.vk_session.mid);
            }
            console.log(this.vk_session)
            // console.log(vk_session.mid)
        },
        methods: {
            vkAuth() {
                VK.Auth.login(
                    (response) => {
                        console.log(response);
                        if (response.status === 'connected') {
                            console.log('авторизация прошла успешно');
                            this.vk_session = response.session;
                            this.getUser(response.session.user.id);
                        } else if (response.status === 'not_authorized') {
                            console.log('пользователь авторизован в ВКонтакте, но не разрешил доступ приложению')
                        } else if (response.status === 'unknown') {
                            console.log('пользователь не авторизован ВКонтакте')
                        }

                    },
                    (VK.access.FRIENDS|VK.access.WALL)
                )
            },
            userItemClick(type) {
                if(type === 'logoutUser') {
                    this.logoutUser();
                }
            },
            logoutUser() {
                VK.Auth.logout(
                    (response) => {
                        if(response.session == null){
                            this.user = '';
                            this.vk_session = '';
                        }
                    }
                );
            },
            getUser(user_id) {
                VK.Api.call('users.get', {user_ids: user_id, v:"5.101", fields:['photo_id', 'verified', 'sex', 'bdate', 'city', 'country', 'home_town', 'has_photo', 'photo_50', 'photo_100', 'photo_200_orig', 'photo_200', 'photo_400_orig', 'photo_max', 'photo_max_orig', 'online', 'domain', 'has_mobile', 'contacts', 'site', 'education', 'universities', 'schools', 'status', 'last_seen', 'followers_count', 'common_count', 'occupation', 'nickname', 'relatives', 'relation', 'personal', 'connections', 'exports', 'activities', 'interests', 'music', 'movies', 'tv', 'books', 'games', 'about', 'quotes', 'can_post', 'can_see_all_posts', 'can_see_audio', 'can_write_private_message', 'can_send_friend_request', 'is_favorite', 'is_hidden_from_feed', 'timezone', 'screen_name', 'maiden_name', 'crop_photo', 'is_friend', 'friend_status', 'career', 'military', 'blacklisted', 'blacklisted_by_me', 'can_be_invited_group']}, (r) => {
                    console.log(r.response[0]);
                    let user = r.response[0];
                    if(user !== undefined) {
                        this.user = user;
                    }
                });
            },
            addLike() {
                VK.Api.call('wall.getComments', {post_id: '109', v:"5.101", need_likes:'1'}, (r) => {
                    console.log(r);
                });
            }
        }
    }
</script>
<style scoped>
    .v-btn {
        outline: none;
    }
</style>
