<template xmlns:color="http://www.w3.org/1999/xhtml">
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
                    v-model="city"
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
            city: '',
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
            multiple (val) {
                if (val) this.model = [this.model]
                else this.model = this.model[0] || 'Foo'
            },
        },
        created() {

        },
        methods: {
            searchGroups() {
                axios.post('/searchGroups', {
                    text:this.groupName,
                    sort: this.sort,
                    city: this.city,
                    type: this.type,
                    market: this.market,
                    future: this.future,
                })
                    .then(res => {
                        this.groups = res.data.response.items;
                        this.groupsCount = res.data.response.count;
                    })
            },
        }
    }
</script>
<style scoped>

</style>
