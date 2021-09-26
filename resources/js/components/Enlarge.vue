<template>
    <v-container class="grey lighten-5 enlarge">
        <v-row class="m-3 enlarge__header">
            <h1 class="enlarge__header_title">Накрутка вконтакте</h1>
            <v-spacer></v-spacer>
            <div class="enlarge__header_action">
                <v-btn color="success">
                    <router-link :to="'/dashboard/sozdat_nakrutka_vk'">Добавить задание</router-link>
                </v-btn>
            </div>
        </v-row>

        <v-row no-gutters>
            <v-col
                cols="12"
                sm="12"
                lg="4"
                class="mb-4 p-2"
            >
                <v-card
                    class="mx-auto enlarge__card"
                    max-width="400"
                >
                    <v-card-title class="title">
                        <span class="title font-weight-bold">Поставить лайк</span>
                    </v-card-title>

                    <v-list class="enlarge__list" >
                        <template v-for="(item, index) in items">
                            <v-list-item
                                class="list-item"
                                :key="item.title"
                            >
                                <v-list-item-avatar>
                                    <v-icon class="list-icon">{{ item.icon }}</v-icon>
                                </v-list-item-avatar>

                                <v-list-item-content>
                                    <v-list-item-title class="list-title">
                                        <a @click="redirectToWorkPage(item, index)" target="_blank" :href="'https://vk.com/' + item.link">{{ item.title }}</a>
                                    </v-list-item-title>
                                    <v-list-item-subtitle>
                                        Награда: {{item.reward}}<v-icon class="ml-1 like-icon" :size="14">mdi-heart-outline</v-icon>
                                    </v-list-item-subtitle>
                                </v-list-item-content>

                                <v-list-item-icon>
                                    <v-icon @click="removeItem(index)" class="delete-icon" :size="22">mdi-close</v-icon>
                                </v-list-item-icon>
                            </v-list-item>
                            <v-divider></v-divider>
                        </template>
                    </v-list>

                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn icon>
                            <v-icon>mdi-heart</v-icon>
                        </v-btn>
                        <v-btn icon>
                            <v-icon>mdi-share-variant</v-icon>
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
    export default {
        data: () => ({
            startWork: false,
            work: null,
            window_focus: null,
            items: [
                // post — запись на стене пользователя или группы;
                // comment — комментарий к записи на стене;
                // photo — фотография;
                // audio — аудиозапись;
                // video — видеозапись;
                // note — заметка;
                // market — товар;
                // photo_comment — комментарий к фотографии;
                // video_comment — комментарий к видеозаписи;
                // topic_comment — комментарий в обсуждении;
                // market_comment — комментарий к товару;
                {
                    id: 1,
                    type: 'video',
                    author_id: '-162074449',
                    object_id: '456240212',
                    icon: 'mdi-camera-outline',
                    link: 'video-162074449_456240212',
                    title: 'Лайкнуть фотографию',
                    reward: 2
                },
                {
                    id: 2,
                    type: 'video',
                    author_id: '-162074449',
                    object_id: '456240212',
                    icon: 'mdi-subtitles-outline',
                    link: 'video-162074449_456240212',
                    title: 'Лайкнуть запись на стене',
                    reward: 4
                },
                {
                    id: 3,
                    type: 'video',
                    author_id: '-162074449',
                    object_id: '456240212',
                    icon: 'mdi-movie-open-outline',
                    link: 'video-162074449_456240212',
                    title: 'Лайкнуть видеозапись',
                    reward: 5
                },
                {
                    id: 4,
                    type: 'video',
                    author_id: '-162074449',
                    object_id: '456240212',
                    icon: 'mdi-shopping-outline',
                    link: 'video-162074449_456240212',
                    title: 'Лайкнуть товар',
                    reward: 2
                },
                {
                    id: 5,
                    type: 'video',
                    author_id: '-162074449',
                    object_id: '456240212',
                    icon: 'mdi-comment-outline',
                    link: 'video-162074449_456240212',
                    title: 'Лайкнуть комментарий',
                    reward: 5
                },
            ],
        }),
        created() {
            window.onblur = () => {
                this.window_focus = false;
                console.log('документ неактивен');
            };
            window.onfocus = () => {
                if(!this.window_focus && this.startWork && this.work) {
                    this.checkWork(this.work)
                }
                this.window_focus = true;
            };

            this.$store.commit('increment');
            console.log(this.$store.state.user);
        },
        mounted() {
            this.applyItemsHover()
        },
        methods: {
            removeItem(index, type = null) {
                this.items.splice(index, 1);
            },
            redirectToWorkPage(item, index) {
                this.startWork = true;
                this.work = {item: item, index: index};
            },
            checkWork(work, repeat = null) {
                console.log(work);
                VK.Api.call('likes.isLiked', {type: work.item.type, v:"5.101", owner_id: work.item.author_id, item_id: work.item.object_id}, (r) => {
                    console.log(r.response.liked); // copied
                    if(r.response.liked === 1) {
                        if(!repeat) {
                            setTimeout(() => {
                                this.checkWork(work, true)
                            }, 30000);
                            this.$store.commit('setBalancePlus', work.item.reward);
                            this.items.splice(work.index, 1);
                        }
                    } else {
                        if(repeat) {
                            this.$store.commit('setBalanceMinus', work.item.reward);
                            this.items.push(work.item);
                            this.applyItemsHover();
                        }
                    }
                    this.startWork = null;
                    this.work = null;
                });
            },
            applyItemsHover() {
                $('.list-item').on('mouseenter', function() {
                    if(window.screen.width > 600) {
                        $(this).find('.delete-icon').show();
                    }
                    $(this).find('.list-title a').addClass('hover');
                });
                $('.list-item').on('mouseleave', function() {
                    if(window.screen.width > 600) {
                        $(this).find('.delete-icon').hide();
                    }
                    $(this).find('.list-title a').removeClass('hover');
                });
            }
        }
    }
</script>

<style scoped>

</style>
