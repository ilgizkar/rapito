<template>
    <v-container class="pl-6 pr-6">
        <v-row class="mt-5">
            <v-col cols="6">
                <v-select
                    dense
                    :items="sortParams"
                    v-model="sort"
                    label="Параметры сортировки"
                    outlined
                ></v-select>
                <v-text-field
                    dense
                    label="Введите название группы"
                    v-model="groupName"
                    outlined
                    :rules="[ v => !!v || 'Заполните обязательное поле']"
                    required
                ></v-text-field>
            </v-col>
            <v-col cols="6">
                <v-select
                    dense
                    :items="groupType"
                    v-model="type"
                    label="Тип сообщества"
                    outlined
                ></v-select>
                <v-text-field
                    dense
                    label="Введите название города"
                    v-model="city_name"
                    outlined
                ></v-text-field>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="4">
                <v-switch v-model="future" class="ma-2" label="Предстоящие события"></v-switch>
            </v-col>
            <v-col cols="4">
                <v-switch v-model="market" class="ma-2" label="С включенными товарами"></v-switch>
            </v-col>
        </v-row>

        <v-btn color="success" @click="searchGroups">Найти</v-btn>

        <v-row class="mt-5">
            <v-list subheader v-if="groups">
                <v-subheader>Найденные группы:  {{ groupsCount }}</v-subheader>

                <v-list-item
                    v-for="group in groups"
                    :key="group.id"
                >
                    <v-list-item-avatar>
                        <v-img :src="group.photo_50"></v-img>
                    </v-list-item-avatar>

                    <v-list-item-content>
                        <a :href="'https://vk.com/' + group.screen_name" target="_blank" style="color: #212121;"><v-list-item-title v-text="group.name"></v-list-item-title></a>
                    </v-list-item-content>

                    <v-list-item-icon>
                        <v-icon color="grey">mdi-cog</v-icon>
                    </v-list-item-icon>
                </v-list-item>
            </v-list>

        </v-row>
    </v-container>

</template>

<script>
    export default {
        data: () => ({
            future: 0,
            market: 0,
            groupName: '',
            groups: '',
            sort: '',
            city_id: '',
            city_name: '',
            type: '',
            groupsCount: 0,
            groupType: [
                {
                    text: 'Группа' ,value: 'group'
                },
                {
                    text: 'Страница' ,value: 'page'
                },
                {
                    text: 'Событие' ,value: 'event'
                }
            ],
            sortParams: [
                {
                    text: 'Сортировать по умолчанию' ,value: 0
                },
                {
                    text: 'Сортировать по скорости роста' ,value: 1
                },
                {
                    text: 'По отношению дневной посещаемости к количеству пользователей' ,value: 2
                },
                {
                    text: 'По отношению количества лайков к количеству пользователей' ,value: 3
                },
                {
                    text: 'По отношению количества комментариев к количеству пользователей' ,value: 4
                },
                {
                    text: 'По отношению количества записей в обсуждениях к количеству пользователей' ,value: 5
                },
            ]
        }),
        computed: {

        },
        watch: {
            city_name (val) {
                this.getCityId(val);
            },
            future (val) {
                if(val == true) {
                    this.future = 1;
                } else {
                    this.future = 0;
                }
            },
            market (val) {
                if(val == true) {
                    this.market = 1;
                } else {
                    this.market = 0;
                }
            }
        },
        created() {

        },
        methods: {
            searchGroups() {
                VK.Api.call('groups.search', {q: this.groupName, v: "5.101", sort: this.sort, city_id: this.city_id, type: this.type, future: this.future, market: this.market}, (r) => {
                    if(r.response.count > 0) {
                        this.groups = r.response.items;
                        this.groupsCount = r.response.count;
                        // this.groups.map((group) => {
                        //     this.getGroupMembers(group.id);
                        // })
                    } else {
                        this.groups = '';
                        this.groupsCount = 0;
                    }
                });
            },
            getCityId(city) {
                VK.Api.call('database.getCities', {q: city, v: "5.101", country_id: 1}, (r) => {
                    if(r.response.count > 0) {
                        this.city_id = r.response.items[0].id;
                    } else {
                        this.city_id = '';
                    }
                });
            },
            getGroupMembers(group_id) {
                VK.Api.call('groups.getMembers', {group_id: group_id, v: "5.101"}, (r) => {
                    console.log(r)
                });
            }
        }
    }
</script>
<style scoped>

</style>
