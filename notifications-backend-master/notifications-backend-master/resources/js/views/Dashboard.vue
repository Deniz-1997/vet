<template>

    <v-app class="body-column">
        <div class="header">
            <div class="header__name">Управление каналом <span class="title-name">{{ channelName }}</span></div>
        </div>

        <table-curd class="mb-10"
                    v-bind:api="table.channel.users.api"
                    v-bind:api-data="table.channel.users.api_data"
                    v-bind:api-url="table.channel.users.url"
                    v-bind:headers="table.channel.users.headers"
                    v-bind:name="table.channel.users.name"
                    v-bind:template-item="table.channel.users.templateItem"
                    v-bind:templates="table.channel.users.templates">
            <template v-slot:default="slotProps">
                <v-container>
                    <v-row>
                        <v-col cols="12">
                            <v-autocomplete :items="completeNameUser" :loading="autocomplete.user.isLoading"
                                            :search-input.sync="autocomplete.user.search" color="black"
                                            hide-selected item-text="name"
                                            item-value="id" label="Имя пользователя"
                                            placeholder="Введите имя пользователя"
                                            v-model="slotProps.edit.user_id"></v-autocomplete>
                            <input type="hidden"
                                   v-model="slotProps.edit.channel_id = channelId">
                        </v-col>
                    </v-row>
                </v-container>
            </template>
        </table-curd>

        <h1 class="v-toolbar__title mb-5">События</h1>

        <v-card class="mb-10">
            <v-tabs
                fixed-tabs
                v-model="event_tab">
                <v-tab>
                    Список
                </v-tab>
                <v-tab>
                    Шаблоны
                </v-tab>
            </v-tabs>

            <v-tabs-items class="mb-10" v-model="event_tab">
                <v-tab-item>
                    <table-curd :classStyle="classNameForTable"
                                v-bind:api="table.events.list.api"
                                v-bind:api-data="table.events.list.api_data"
                                v-bind:api-url="table.events.list.url"
                                v-bind:headers="table.events.list.headers"
                                v-bind:name="table.events.list.name"
                                v-bind:template-item="table.events.list.templateItem"
                                v-bind:templates="table.events.list.templates">
                        <template v-slot:default="slotProps">

                            <v-container>
                                <v-row>
                                    <v-col cols="6">
                                        <v-text-field label="Имя" v-model="slotProps.edit.name"></v-text-field>
                                    </v-col>
                                    <v-col cols="6">
                                        <v-switch label="Учитывать иерархию"
                                                  v-model="slotProps.edit.hierarchy"></v-switch>
                                    </v-col>
                                </v-row>
                            </v-container>
                        </template>
                    </table-curd>
                </v-tab-item>
                <v-tab-item>
                    <table-curd :classStyle="classNameForTable"
                                v-bind:api="table.events.template.api"
                                v-bind:api-data="table.events.template.api_data"
                                v-bind:api-url="table.events.template.url"
                                v-bind:headers="table.events.template.headers"
                                v-bind:name="table.events.template.name"
                                v-bind:template-item="table.events.template.templateItem"
                                v-bind:templates="table.events.template.templates">
                        <template v-slot:default="slotProps">

                            <v-container>
                                <v-row>
                                    <v-col cols="12">
                                        <v-autocomplete :items="completeEvent" :loading="autocomplete.event.isLoading"
                                                        :search-input.sync="autocomplete.event.search" color="black"
                                                        hide-selected item-text="name"
                                                        item-value="id" label="Событие" placeholder="Поиск события"
                                                        v-model="slotProps.edit.event_id"></v-autocomplete>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-autocomplete :items="completeTemplate"
                                                        :loading="autocomplete.template.isLoading"
                                                        :search-input.sync="autocomplete.template.search" color="black"
                                                        hide-selected item-text="name"
                                                        item-value="id" label="Шаблон" placeholder="Поиск шаблона"
                                                        v-model="slotProps.edit.template_id"></v-autocomplete>
                                    </v-col>
                                </v-row>
                            </v-container>
                        </template>
                    </table-curd>
                </v-tab-item>
            </v-tabs-items>
        </v-card>

        <h1 class="v-toolbar__title mb-5">Шаблоны</h1>

        <v-card class="mb-10">
            <v-tabs
                fixed-tabs
                v-model="template_tab">
                <v-tab>
                    Список
                </v-tab>
                <v-tab>
                    Группы пользователей
                </v-tab>
            </v-tabs>

            <v-tabs-items v-model="template_tab">
                <v-tab-item>
                    <table-curd :classStyle="classNameForTable"
                                v-bind:api="table.template.list.api"
                                v-bind:api-data="table.template.list.api_data"
                                v-bind:api-url="table.template.list.url"
                                v-bind:headers="table.template.list.headers"
                                v-bind:name="table.template.list.name"
                                v-bind:template-item="table.template.list.templateItem"
                                v-bind:templates="table.template.list.templates">
                        <template v-slot:default="slotProps">

                            <v-container>
                                <v-row>
                                    <v-col cols="12">
                                        <v-text-field label="Имя" v-model="slotProps.edit.name"></v-text-field>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-color-picker class="ma-2" hide-canvas hide-inputs show-swatches
                                                        v-model="slotProps.edit.color"></v-color-picker>
                                    </v-col>
                                    <v-col cols="4">
                                        <v-switch label="Показывать статус"
                                                  v-model="slotProps.edit.show_status_notify"></v-switch>
                                    </v-col>
                                    <v-col cols="4">
                                        <v-switch label="Показывать дату" v-model="slotProps.edit.show_date"></v-switch>
                                    </v-col>
                                    <v-col cols="4">
                                        <v-switch label="Показывать время"
                                                  v-model="slotProps.edit.show_time"></v-switch>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-text-field label="Формат даты" v-model="slotProps.edit.format_date"
                                                      value="YYYY-MM-DD H:i:s"></v-text-field>
                                    </v-col>
                                </v-row>
                            </v-container>
                        </template>
                    </table-curd>
                </v-tab-item>
                <v-tab-item>
                    <table-curd :classStyle="classNameForTable"
                                v-bind:api="table.template.groupUser.api"
                                v-bind:api-data="table.template.groupUser.api_data"
                                v-bind:api-url="table.template.groupUser.url"
                                v-bind:headers="table.template.groupUser.headers"
                                v-bind:name="table.template.groupUser.name"
                                v-bind:template-item="table.template.groupUser.templateItem"
                                v-bind:templates="table.template.groupUser.templates">
                        <template v-slot:default="slotProps">

                            <v-container>
                                <v-row>
                                    <v-col cols="12">
                                        <v-autocomplete :items="completeTemplate"
                                                        :loading="autocomplete.template.isLoading"
                                                        :search-input.sync="autocomplete.template.search" color="black"
                                                        hide-selected item-text="name"
                                                        item-value="id" label="Имя шаблона"
                                                        placeholder="Введите имя шаблона"
                                                        v-model="slotProps.edit.template_id"></v-autocomplete>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-autocomplete :items="completeNameGroup"
                                                        :loading="autocomplete.group.isLoading"
                                                        :search-input.sync="autocomplete.group.search" color="black"
                                                        hide-selected item-text="name"
                                                        item-value="id" label="Имя группы пользователя"
                                                        placeholder="Введите имя группы"
                                                        v-model="slotProps.edit.group_id"></v-autocomplete>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-text-field label="Приоритет"
                                                      min="1" type="number"
                                                      v-model="slotProps.edit.priority"></v-text-field>
                                    </v-col>
                                </v-row>
                            </v-container>
                        </template>
                    </table-curd>
                </v-tab-item>
            </v-tabs-items>
        </v-card>
    </v-app>
</template>

<script>
    export default {
        data() {
            return {
                channelName: '',
                classNameForTable: 'evelation-0',
                event_tab: null,
                template_tab: null,
                table: table,
                channelId: 0,
                api_data: {
                    order: JSON.stringify(['id', 'ASC'])
                },
                showPassword: false,
                autocomplete: {
                    event: {start_loading_entries: false, isLoading: false, entries: [], search: null},
                    template: {start_loading_entries: false, isLoading: false, entries: [], search: null},
                    group: {start_loading_entries: false, isLoading: false, entries: [], search: null},
                    user: {start_loading_entries: false, isLoading: false, entries: [], search: null},
                    roles: {start_loading_entries: false, isLoading: false, entries: [], search: null},
                },
            }
        },

        computed: {
            completeTemplate() {
                return this.methodComplete(this.autocomplete.template, null,
                    '/api/v2/template/list?api_token=' + window.auth.token + '&paginator=false&limit=10000',
                    null);
            },
            completeNameGroup() {
                return this.methodComplete(this.autocomplete.group, null,
                    '/api/v2/dictionary/group-users?api_token=' + window.auth.token + '&paginator=false&limit=10000',
                    null);
            },
            completeEvent() {
                return this.methodComplete(this.autocomplete.event, null,
                    '/api/v2/events/list?api_token=' + window.auth.token + '&paginator=false&limit=10000',
                    null);
            },
            completeGroups() {
                let callback = (data) => {
                    this.autocomplete.roles.entries.map(e => {
                        return {
                            name: e.name,
                            group_id: typeof e.id !== "undefined" ? e.id : e.group_id
                        }
                    });
                };

                return this.methodComplete(this.autocomplete.roles, callback,
                    '/api/v2/dictionary/group-users?api_token=' + window.auth.token + '&paginator=false&limit=10000',
                    callback);
            },
            completeRoles() {
                let callback = (data) => {
                    this.autocomplete.roles.entries.map(e => {
                        return {name: e.name + '(' + e.guard_name + ')', id: e.id}
                    });
                };

                return this.methodComplete(this.autocomplete.roles, callback,
                    '/api/v2/user/roles?api_token=' + window.auth.token + '&paginator=false&limit=10000',
                    callback);

            },
            completeNameUser() {
                return this.methodComplete(this.autocomplete.user, null, '/api/v2/user/list?api_token=' + window.auth.token + '&paginator=false&limit=10000');
            },
        },

        watch: {
            'autocomplete.event.search': function (val) {
                window.methodSearch(val, this.completeEvent, this.autocomplete.event, '/api/v2/events/list?api_token=' + window.auth.token + '' +
                    '&paginator=false&where=' + JSON.stringify([
                        ['name', 'like', val]
                    ]), this.channelId);
            },
            'autocomplete.template.search': function (val) {
                window.methodSearch(val, this.completeTemplate, this.autocomplete.template, '/api/v2/template/list?api_token=' + window.auth.token + '' +
                    '&paginator=false&where=' + JSON.stringify([
                        ['name', 'like', val]
                    ]), this.channelId);
            },
            'autocomplete.roles.search': function (val) {
                window.methodSearch(val, this.completeRoles, this.autocomplete.roles, '/api/v2/user/roles?api_token=' + window.auth.token + '' +
                    '&paginator=false&where=' + JSON.stringify([
                        ['name', 'like', val]
                    ]), this.channelId);
            },
            'autocomplete.group.search': function (val) {
                window.methodSearch(val, this.completeNameGroup, this.autocomplete.group, '/api/v2/dictionary/group-users?api_token=' + window.auth.token + '' +
                    '&paginator=false&where=' + JSON.stringify([
                        ['name', 'like', val]
                    ]), this.channelId);
            },
            'autocomplete.user.search': function (val) {
                window.methodSearch(val, this.completeNameUser, this.autocomplete.user, '/api/v2/user/list?api_token=' + window.auth.token + '' +
                    '&paginator=false&where=' + JSON.stringify([
                        ['name', 'like', val]
                    ]), this.channelId);
            },
        },

        created() {
            window.fetchAutocomplete('/api/v2/user/roles?api_token=' + window.auth.token + '' +
                '&paginator=false&limit=100', this.autocomplete.roles, this.channelId);

            window.fetchAutocomplete('/api/v2/dictionary/group-users?api_token=' + window.auth.token + '' +
                '&paginator=false&limit=100', this.autocomplete.group, this.channelId);
        },

        methods: {
            methodComplete(autocomplete, callback, url) {
                if (typeof autocomplete.start_loading_entries !== "undefined" && !autocomplete.start_loading_entries) {
                    autocomplete.start_loading_entries = true;
                    window.fetchAutocomplete(url, autocomplete, this.channelId, (data) => {
                        if (typeof callback !== "undefined" && callback !== null) {
                            callback(data)
                        } else {
                            autocomplete.entries.map(e => {
                                const name = e.name;
                                return Object.assign({}, e, {name})
                            });
                        }
                    })
                }

                return autocomplete.entries.map(e => {
                    if (typeof callback !== "undefined" && callback !== null) {
                        return callback(e);
                    } else {
                        const name = e.name;
                        return Object.assign({}, e, {name})
                    }
                });
            }
        },

        mounted() {
            Event.$on('clearSearchInput', () => {
                $.each(this.autocomplete, (e, v) => {
                    v.search = null;
                });
            });

            Event.$on('changeChannelId', () => {
                this.channelId = parseInt(window.localStorage.getItem('channel_id'));
                this.channelName = window.localStorage.getItem('channel_name');
                Event.$emit('changeData');
            });
        },
    }
</script>

<style scoped>
    .title-name {
        text-transform: capitalize;
    }
</style>
