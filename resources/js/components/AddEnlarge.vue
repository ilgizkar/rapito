<template>
    <v-container class="grey lighten-5 enlarge">
        <v-row class="m-3 enlarge__header">
            <h1 class="enlarge__header_title">Добавление задания</h1>
            <v-spacer></v-spacer>
            <div class="enlarge__header_action">

            </div>
        </v-row>

        <v-row no-gutters>
            <v-col
                cols="12"
                sm="12"
                lg="5"
                class="mb-4 p-2"
            >
                <v-select
                    dense
                    :items="workTypes"
                    v-model="form.work_type"
                    label="Тип задания"
                    outlined
                ></v-select>
                <v-select
                    dense
                    :items="objectTypes"
                    v-model="form.object_type"
                    label="Тип объекта"
                    outlined
                ></v-select>
                <v-text-field
                    dense
                    label="Введите ссылку на объект"
                    messages="Зайдите в свои альбомы, альбомы вашего друга или группы
Найдите в альбомах фотографию, для которой хотите получить лайки, и откройте её
В адресной строке браузера появится адрес вида http://vk.com/photo454162779_265
Скопируйте полностью этот адрес в поле, расположенное прямо под этой инструкцией"
                    v-model="form.object_link"
                    outlined
                ></v-text-field>
                <v-select
                    dense
                    :items="speedTypes"
                    v-model="form.speed_type"
                    label="Скорость выполнения"
                    outlined
                ></v-select>
                <v-text-field
                    dense
                    v-model="form.work_result"
                    label="Ожидаемый результат"
                    min="1"
                    step="1"
                    type="number"
                    outlined
                ></v-text-field>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
    export default {
        data () {
            return {
                form: {
                    object_type: null,
                    work_type: null,
                    object_link: null,
                    speed_type: null,
                    work_result: 1,
                },
                objectTypes: [
                    {
                        text: 'Фотография' ,value: 1
                    },
                    {
                        text: 'Видеозапись' ,value: 2
                    },
                    {
                        text: 'Запись на стене' ,value: 3
                    },
                    {
                        text: 'Товар' ,value: 4
                    },
                    {
                        text: 'Комментарий' ,value: 5
                    },
                    {
                        text: 'Группа' ,value: 6
                    },
                    {
                        text: 'Пользователь' ,value: 7
                    },
                ],
                workTypes: [
                    {
                        text: 'Поставить лайк' ,value: 1
                    },
                    {
                        text: 'Сделать репост' ,value: 2
                    },
                    {
                        text: 'Подписаться/вступить' ,value: 3
                    },
                    {
                        text: 'Добавить в друзья' ,value: 4
                    },
                    {
                        text: 'Написать комментарий' ,value: 5
                    },
                ],
                speedTypes: [
                    {
                        text: 'Низкая' ,value: 1
                    },
                    {
                        text: 'Средняя' ,value: 2
                    },
                    {
                        text: 'Максимальная' ,value: 3
                    },
                ]
            }
        },
        created() {

        },
        mounted() {

        },
        computed: {

        },
        watch: {
            'form.work_type': function (newVal, oldVal){
                this.form.object_type = null;
            }
        },
        methods: {

        }
    }
</script>

<style scoped>

</style>
