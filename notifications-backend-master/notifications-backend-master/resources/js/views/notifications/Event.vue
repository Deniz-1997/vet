<template>
    <v-app class="body-column">
        <table-curd v-bind:api="table.notifications.events.api"
                    v-bind:api-data="table.notifications.events.api_data"
                    v-bind:api-url="table.notifications.events.url"
                    v-bind:headers="table.notifications.events.headers"
                    v-bind:name="table.notifications.events.name"
                    v-bind:template-item="table.notifications.events.templateItem"
                    v-bind:templates="table.notifications.events.templates">
            <template v-slot:default="slotProps">
                <v-container>
                    <v-row>
                        <v-col cols="12">
                            <v-autocomplete :items="completeNameEvent" :loading="autocomplete.event.isLoading"
                                            :search-input.sync="autocomplete.event.search" color="black"
                                            hide-selected item-text="name"
                                            item-value="id" label="Имя события" placeholder="Введите имя события"
                                            v-model="slotProps.edit.event_id"></v-autocomplete>
                        </v-col>
                        <v-col cols="12">
                            <v-autocomplete :items="completeNameNotification"
                                            :loading="autocomplete.notifications.isLoading"
                                            :search-input.sync="autocomplete.notifications.search" color="black"
                                            hide-selected item-text="name"
                                            item-value="id" label="Имя оповещения" placeholder="Введите имя оповещения"
                                            v-model="slotProps.edit.notifications_id"></v-autocomplete>
                        </v-col>
                        <v-col cols="12">
                            <v-text-field label="Текст" v-model="slotProps.edit.text"></v-text-field>
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
                autocomplete: {
                    event: {start_loading_entries: false, isLoading: false, entries: [], search:null},
                    notifications: {start_loading_entries: false, isLoading: false, entries: [], search:null},
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
            completeNameEvent() {
                return this.autocomplete.event.entries.map(e => {
                    const name = e.name;
                    return Object.assign({}, e, {name})
                });
            },
            completeNameNotification() {
                return this.autocomplete.notifications.entries.map(e => {
                    const name = e.name;
                    return Object.assign({}, e, {name})
                });
            },
        },


        watch: {
            'autocomplete.template.notifications': function (val) {
                window.methodSearch(val, this.completeNameNotification, this.autocomplete.notifications,
                    '/api/v2/notifications/list?api_token=' + window.auth.token + '' +
                    '&paginator=false&where=' + JSON.stringify([
                        ['name', 'like', val]
                    ]));
            },
            'autocomplete.event.search': function (val) {
                window.methodSearch(val, this.completeEvent, this.autocomplete.event,
                    '/api/v2/events/list?api_token=' + window.auth.token + '' +
                    '&paginator=false&where=' + JSON.stringify([
                        ['name', 'like', val]
                    ]));
            },
        },

        created() {
            window.fetchAutocomplete('/api/v2/notifications/list?api_token=' + window.auth.token + '&paginator=false&limit=10000',
                this.autocomplete.notifications);

            window.fetchAutocomplete('/api/v2/events/list?api_token=' + window.auth.token + '&paginator=false&limit=10000',
                this.autocomplete.event);
        }
    }
</script>

<style scoped>
</style>
