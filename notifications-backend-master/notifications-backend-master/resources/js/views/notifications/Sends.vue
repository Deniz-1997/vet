<template>
    <v-app class="body-column">
        <table-curd v-bind:api="table.notifications.sends.api"
                    v-bind:api-data="table.notifications.sends.api_data"
                    v-bind:api-url="table.notifications.sends.url"
                    v-bind:headers="table.notifications.sends.headers"
                    v-bind:name="table.notifications.sends.name"
                    v-bind:template-item="table.notifications.sends.templateItem"
                    v-bind:templates="table.notifications.sends.templates">
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
                        </v-col>
                        <v-col cols="12">
                            <v-autocomplete :items="completeNameNotification"
                                            :loading="autocomplete.notifications.isLoading"
                                            :search-input.sync="autocomplete.notifications.search" color="black"
                                            hide-selected item-text="name"
                                            item-value="id" label="Имя оповещения" placeholder="Введите имя оповещения"
                                            v-model="slotProps.edit.notify_event_id"></v-autocomplete>
                        </v-col>
                        <v-col cols="12">
                            <datetime format="yyyy-MM-dd HH:mm:ss"
                                      placeholder="Выберите дату и время"
                                      type="datetime"
                                      v-model="slotProps.edit.sended_date"></datetime>
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
                date: null,
                autocomplete: {
                    user: {start_loading_entries: false, isLoading: false, entries: [], search: null},
                    notifications: {start_loading_entries: false, isLoading: false, entries: [], search: null},
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
            completeNameUser() {
                return this.autocomplete.user.entries.map(e => {
                    const name = e.name;
                    return Object.assign({}, e, {name})
                });
            },
            completeNameNotification() {
                return this.autocomplete.notifications.entries.map(e => {
                    console.log(e);
                    const name = 'ID: ' + e.id + ': ' + (typeof e.event.name !== undefined)? e.event.name: ' - '
                        + ' - ' + (typeof e.notification.name !== undefined)? e.notification.name: ' - ';
                    return Object.assign({}, e, {name})
                });
            },
        },

        watch: {
            'autocomplete.user.user': function (val) {
                window.methodSearch(val, this.completeNameUser, this.autocomplete.user,
                    '/api/v2/user/list?api_token=' + window.auth.token + '' +
                    '&paginator=false&where=' + JSON.stringify([
                        ['name', 'like', val]
                    ]));
            },
            'autocomplete.user.notifications': function (val) {
                window.methodSearch(val, this.completeNameNotification, this.autocomplete.notifications,
                    '/api/v2/notifications/events?api_token=' + window.auth.token + '' +
                    '&paginator=false&with=' + JSON.stringify(['event', 'notification']));
            }
        },

        created() {
            window.fetchAutocomplete('/api/v2/notifications/events?api_token=' + window.auth.token +
                '&paginator=false&limit=10000&with=' + JSON.stringify(['event', 'notification']),
                this.autocomplete.notifications);

            window.fetchAutocomplete('/api/v2/user/list?api_token=' + window.auth.token + '&paginator=false&limit=10000',
                this.autocomplete.user);
        }
    }
</script>

<style scoped>
</style>
