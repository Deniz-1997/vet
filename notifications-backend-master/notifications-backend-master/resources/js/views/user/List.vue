<template>
    <v-app class="body-column">
        <table-curd v-bind:api="table.user.list.api"
                    v-bind:api-data="table.user.list.api_data"
                    v-bind:api-url="table.user.list.url"
                    v-bind:headers="table.user.list.headers"
                    v-bind:name="table.user.list.name"
                    v-bind:template-item="table.user.list.templateItem"
                    v-bind:templates="table.user.list.templates">
            <template v-slot:default="slotProps">
                <v-container>
                    <v-row>
                        <v-col cols="6">
                            <v-text-field label="Имя" v-model="slotProps.edit.name"></v-text-field>
                        </v-col>
                        <v-col cols="6">
                            <v-text-field
                                :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                                :type="showPassword ? 'text' : 'password'"
                                @click:append="showPassword = !showPassword"
                                autocomplete="new-password"
                                hint="Должен содержать минимум 8 символов"
                                label="Пароль"
                                name="input-10-1"
                                v-model="slotProps.edit.password">
                            </v-text-field>
                        </v-col>
                        <v-col cols="6">
                            <v-text-field label="Почта" v-model="slotProps.edit.email"></v-text-field>
                        </v-col>
                        <v-col cols="6">
                            <v-text-field autocomplete="off" label="Телефон"
                                          v-model="slotProps.edit.phone"></v-text-field>
                        </v-col>

                        <v-col cols="12">
                            <v-autocomplete :items="completeGroups"
                                            :loading="autocomplete.groups.isLoading"
                                            :search-input.sync="autocomplete.groups.search"
                                            chips
                                            color="black"
                                            dense
                                            item-text="name"
                                            item-value="group_id"
                                            label="Выберите группы"
                                            multiple
                                            outlined placeholder="Выберите группы" small-chips
                                            v-model="slotProps.edit.groups"></v-autocomplete>
                        </v-col>

                        <v-col cols="12">
                            <v-autocomplete :items="completeOrganizations"
                                            :loading="autocomplete.organizations.isLoading"
                                            :search-input.sync="autocomplete.organizations.search"
                                            color="black"
                                            item-text="name"
                                            item-value="id"
                                            label="Выберите организацию"
                                            outlined placeholder="Выберите организацию" small-chips
                                            v-model="slotProps.edit.organization_id"></v-autocomplete>
                        </v-col>

                        <v-col cols="12" v-show="viewRole">
                            <v-autocomplete :items="completeRoles" :loading="autocomplete.roles.isLoading"
                                            :search-input.sync="autocomplete.roles.search" chips
                                            color="black" dense hide-selected item-text="name" item-value="id"
                                            label="Выберите роли" multiple
                                            outlined placeholder="Выберите роли" small-chips
                                            v-model="slotProps.edit.roles"></v-autocomplete>
                        </v-col>
                    </v-row>
                </v-container>
            </template>
        </table-curd>
    </v-app>
</template>

<script>
    export default {
        data() {
            return {
                table: table,
                viewRole: true,
                showPassword: false,
                name: "Пользователи",
                autocomplete: {
                    organizations: {start_loading_entries: false, isLoading: false, entries: [], search: null},
                    roles: {start_loading_entries: false, isLoading: false, entries: [], search: null},
                    groups: {start_loading_entries: false, isLoading: false, entries: [], search: null}
                },
            }
        },
        mounted() {
            Event.$on('clearSearchInput', () => {
                $.each(this.autocomplete, (e, v) => {
                    v.search = null;
                });
            });
        },
        computed: {
            completeGroups() {
                return window.completeMethod(this.autocomplete.groups,
                    (e) => {
                        return {
                            name: e.name,
                            group_id: typeof e.id !== "undefined" ? e.id : e.group_id
                        }
                    },
                    '/api/v2/dictionary/group-users?api_token=' + window.auth.token + '&paginator=false&limit=10000');
            },
            completeOrganizations() {
                return window.completeMethod(this.autocomplete.organizations,
                    null,
                    '/api/v2/dictionary/organizations?api_token=' + window.auth.token + '&paginator=false&limit=10000');
            },
            completeRoles() {
                return window.completeMethod(this.autocomplete.roles,
                    (e) => {
                        let guard = (e.guard_name === 'web') ? 'Вход в личный кабинет' : 'Только запрос к API'
                        return {
                            name: e.name + ' (' + guard + ')',
                            id: e.id
                        }
                    },
                    '/api/v2/user/roles?api_token=' + window.auth.token + '&paginator=false&limit=10000');
            },
        },
        watch: {
            'autocomplete.organizations.search': function (val) {
                window.methodSearch(val, this.completeOrganizations, this.autocomplete.organizations,
                    '/api/v2/dictionary/organizations?api_token=' + window.auth.token + '&paginator=false&where=' +
                    JSON.stringify([
                        ['name', 'like', val]
                    ]));
            },
            'autocomplete.groups.search': function (val) {
                window.methodSearch(val, this.completeGroups, this.autocomplete.groups,
                    '/api/v2/dictionary/group-users?api_token=' + window.auth.token + '&paginator=false&where=' +
                    JSON.stringify([
                        ['name', 'like', val]
                    ]));
            },
            'autocomplete.roles.search': function (val) {
                window.methodSearch(val, this.completeRoles, this.autocomplete.roles,
                    '/api/v2/user/roles?api_token=' + window.auth.token + '&paginator=false&where=' +
                    JSON.stringify([
                        ['name', 'like', val]
                    ]));
            },
        },

        created() {
            window.fetchAutocomplete('/api/v2/dictionary/organizations?api_token=' + window.auth.token + '' +
                '&paginator=false&limit=1000', this.autocomplete.organizations);

            window.fetchAutocomplete('/api/v2/user/roles?api_token=' + window.auth.token + '' +
                '&paginator=false&limit=1000', this.autocomplete.roles);

            window.fetchAutocomplete('/api/v2/dictionary/group-users?api_token=' + window.auth.token + '' +
                '&paginator=false&limit=1000', this.autocomplete.groups);
        }
    }
</script>

<style scoped>
</style>
