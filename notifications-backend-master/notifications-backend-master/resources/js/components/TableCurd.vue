<template>
    <v-card>
        <v-data-table :headers="headers"
                      :items="items"
                      :options.sync="params"
                      :items-per-page="itemsPerPage"
                      :loading="loading"
                      :class="classTable"
                      :server-items-length="totalItems">
            <template v-slot:top>
                <v-toolbar color="white" flat>

                    <v-toolbar-title>{{name}}</v-toolbar-title>

                    <v-divider class="mx-4" inset vertical></v-divider>

                    <v-spacer></v-spacer>

                    <v-dialog max-width="500px" v-model="dialog">
                        <template v-slot:activator="{ on }">
                            <v-btn class="mb-2" color="primary" dark v-on="on">Создать запись</v-btn>
                        </template>
                        <v-card>
                            <v-card-title>
                                <span class="headline">{{ formTitle }}</span>
                            </v-card-title>

                            <v-card-text>
                                <slot v-bind:edit="edit">Не определен шаблон создания или редактирования записи</slot>
                            </v-card-text>

                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn @click="close" color="blue darken-1" text>Отмена</v-btn>
                                <v-btn @click="save" color="blue darken-1" text>Сохранить</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>

                </v-toolbar>
            </template>


            <template v-slot:item.action="{ item }">
                <v-icon @click="editItem(item)" class="mr-2" small>edit</v-icon>
                <v-icon @click="deleteItem(item)" small>delete</v-icon>
            </template>

            <template v-for="template in templates" v-slot:[template.name]="{item}">
                <div v-html="templateConvert(template.value, item)"></div>
            </template>

        </v-data-table>


        <v-snackbar :color="color" :timeout="timeout" :top="'top'" v-model="snackbar">
            <div v-html="text"></div>
            <v-btn @click="snackbar = false" dark text>
                Закрыть
            </v-btn>
        </v-snackbar>
    </v-card>
</template>

<script>
    export default {
        name: "List",
        props: {
            api: {
                type: Function,
                required: true,
            },
            templateItem: {
                type: Object,
                required: true,
            },
            templates: {
                type: Array
            },
            apiData: {
                type: Object,
            },
            headers: {
                type: Array,
                required: true,
            },
            apiUrl: {
                type: String,
                required: true,
            },
            classStyle: {
                type: String,
                default: 'elevation-4',
            },
            name: {
                type: String,
                default: 'Список записей',
            },
        },
        data() {
            return {
                params: {},
                edit: this.templateItem,
                def: this.templateItem,
                classTable: this.classStyle,
                dictClientTypeSearch: null,
                itemsPerPage: 10,
                timeout: 5000,
                color: '',
                text: '',
                snackbar: false,
                show: false,
                search: "",
                totalItems: 0,
                items: [],
                loading: true,
                dialog: false,
                pagination: {},
                editedIndex: -1,
            };
        },

        watch: {

            params: {
                handler() {
                    this.getDataFromApi().then(data => {
                        console.log(data);
                        this.items = data.items;
                        this.totalItems = data.total;
                    });
                },
                deep: true
            },

            dialog(val) {
                val || this.close()
            },
        },

        mounted() {
            this.def = Object.assign({}, this.edit);

            this.getDataFromApi().then(data => {
                this.items = data.items;
                this.totalItems = data.total;
            }).catch(error => {
                this.items = [];
                this.totalItems = 0;
            });

            Event.$on('changeData', () => {
                if (typeof this.apiData.where === "undefined") {
                    this.apiData.where = JSON.stringify([
                        ['channel_id', '=', parseInt(window.localStorage.getItem('channel_id'))]
                    ]);
                }

                let w = JSON.parse(this.apiData.where);
                let add_channel = true;


                for (let i = 0; i < w.length; i++) {
                    if (w[i][0] === 'channel_id') {
                        w[i][2] = parseInt(window.localStorage.getItem('channel_id'));

                        add_channel = false;
                    }
                }

                if (add_channel) {
                    w.push(['channel_id', '=', parseInt(window.localStorage.getItem('channel_id'))]);
                }

                this.apiData.where = JSON.stringify(w);

                let p = this.getDataFromApi();
                console.log(p);

                p.then(data => {
                    console.log(data);
                    this.items = data.items;
                    this.totalItems = data.total;
                });
            });
        },

        computed: {
            formTitle() {
                return this.editedIndex === -1 ? 'Новая запись' : 'Редактирование записи'
            },
        },

        methods: {
            catch(error) {
                let msg = this.getErrorText(error);
                this.showSnackbar('Произошла ошибка. <br>' + msg, 'red');
            },

            templateConvert(e, item) {
                return e(item);
            },

            getDataFromApi() {
                this.loading = true;
                let api = this.api;

                return new Promise((resolve, reject) => {
                    const {sortBy, sortDesc, descending, page, itemsPerPage} = this.params;
                    this.apiData.count = itemsPerPage;
                    this.apiData.page = page;
                    if (sortBy.length > 0) {
                        let sortByDesc_ = typeof sortDesc[0] === "undefined" ? true : sortDesc[0];

                        let order = JSON.parse(this.apiData.order);

                        if (order.length > 0) {
                            order[0] = sortBy[0];

                            order[1] = !sortByDesc_ ? 'DESC' : 'ASC';
                        } else {
                            order = [sortBy[0], !sortByDesc_ ? 'DESC' : 'ASC'];
                        }

                        this.apiData.order = JSON.stringify(order);
                    }

                    api.get(this.apiData).then(response => {

                        let search = this.search.trim().toLowerCase();

                        let {data, total} = response;

                        let items = data;

                        if (search) {
                            items = items.filter(item => Object.values(item).join(",").toLowerCase().includes(search));
                        }

                        if (this.params.sortBy) {
                            items = items.sort((a, b) => {
                                const sortA = a[sortBy];
                                const sortB = b[sortBy];

                                if (descending) {
                                    if (sortA < sortB) return 1;
                                    if (sortA > sortB) return -1;
                                    return 0;
                                } else {
                                    if (sortA < sortB) return -1;
                                    if (sortA > sortB) return 1;
                                    return 0;
                                }
                            });
                        }

                        setTimeout(() => {
                            this.loading = false;
                            resolve({items, total});
                        }, 100);

                    }).catch(error => {
                        this.loading = false;
                        reject()
                    });
                });
            },

            getErrorText(data) {
                let msg = 'Неизвестная ошибка: ' + JSON.stringify(data);
                if (typeof data.errors !== "undefined" && typeof data.errors.message !== "undefined") {
                    msg = '';

                    if (data.errors.message instanceof Array || data.errors.message instanceof Object) {
                        $.each(data.errors.message, (i, v) => {
                            if (v instanceof Array && v.length > 0) {
                                msg += v[0] + "<br>";
                            }
                        });
                    } else {
                        msg = data.errors.message;
                    }
                }
                return msg;
            },

            editItem(item) {
                this.editedIndex = this.items.indexOf(item);
                this.edit = Object.assign({}, item);
                this.dialog = true


                Event.$emit('editItem', item);
            },

            deleteItem(item) {

                const index = this.items.indexOf(item);

                if (confirm('Вы действительно хотите удалить эту запись ?')) {
                    this.api(item.id).delete().then(response => {
                        if (response) {
                            this.showSnackbar('Запись успешно удалена', 'success');
                            this.items.splice(index, 1)
                        } else {
                            this.showSnackbar('Ошибка при удалении записи', 'orange');
                        }
                    }).catch(this.catch);
                }
            },

            close() {
                this.dialog = false;
                this.edit = Object.assign({}, this.def);
                this.editedIndex = -1;
                Event.$emit('clearSearchInput');
            },

            showSnackbar(text, color) {
                this.text = text;
                this.color = color;
                this.snackbar = true;
            },

            save() {
                if (this.editedIndex > -1) {
                    this.api(this.edit.id).put(this.edit).then(response => {
                        if (response.status) {
                            this.api(response.data.id).get(this.apiData).then(response => {
                                if (response.status) {
                                    Object.assign(this.items[this.editedIndex], response.data);
                                    this.showSnackbar('Запись успешно обновлена', 'success');
                                    this.close();
                                } else {
                                    this.showSnackbar('Ошибка обновления записи', 'red');
                                }
                            }).catch(this.catch);

                        } else {
                            this.showSnackbar('Ошибка обновления записи', 'orange');
                        }
                    }).catch(this.catch);
                } else {
                    this.api().post(this.edit).then(response => {
                        if (response.status) {
                            this.showSnackbar('Запись успешно создана', 'success');
                            this.close();
                            this.getDataFromApi().then(data => {
                                this.items = data.items;
                                this.totalItems = data.total;
                            });
                        } else {
                            this.showSnackbar('Ошибка создания записи', 'orange');
                        }
                    }).catch(this.catch);
                }
            },
        }
    }
</script>

<style scoped>
    hr.mx-4.v-divider.v-divider--inset.v-divider--vertical.theme--light {
        display: none;
    }

    button.mb-2.v-btn.v-btn--contained.v-size--default.primary {
        border: 0;
        background: #0d51b6;
        padding: 0 20px;
        display: block;
        color: #fff !important;
        font: 400 14px/40px 'Roboto';
        height: 40px;
        -webkit-border-radius: 4px;
        border-radius: 4px;
        text-decoration: none;
        text-decoration: none;
        text-align: center;
    }
</style>
