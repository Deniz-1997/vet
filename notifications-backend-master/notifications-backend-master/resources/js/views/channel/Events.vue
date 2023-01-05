<template>
    <v-app class="body-column">

        <table-curd v-bind:api="table.channel.event.api"
                    v-bind:api-data="table.channel.event.api_data"
                    v-bind:api-url="table.channel.event.url"
                    v-bind:headers="table.channel.event.headers"
                    v-bind:name="table.channel.event.name"
                    v-bind:template-item="table.channel.event.templateItem"
                    v-bind:templates="table.channel.event.templates">

            <template v-slot:default="slotProps">
                <v-container>
                    <v-row>
                        <v-col cols="12">
                            <v-autocomplete :items="completeNameChannel" :loading="autocomplete.channel.isLoading"
                                            :search-input.sync="autocomplete.channel.search" color="black"
                                            hide-selected item-text="name"
                                            item-value="id" label="Имя канала" placeholder="Введите имя канала"
                                            v-model="slotProps.edit.channel_id"></v-autocomplete>
                        </v-col>
                        <v-col cols="12">
                            <v-autocomplete :items="completeNameEvent" :loading="autocomplete.event.isLoading"
                                            :search-input.sync="autocomplete.event.search" color="black"
                                            hide-selected item-text="name"
                                            item-value="id" label="Название события" placeholder="Название события"
                                            v-model="slotProps.edit.event_id"></v-autocomplete>
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
                    channel: {start_loading_entries: false, isLoading: false, entries: [], search: null},
                    event: {start_loading_entries: false, isLoading: false, entries: [], search: null}
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
            completeNameChannel() {
                return this.autocomplete.channel.entries.map(e => {
                    const name = e.name;
                    return Object.assign({}, e, {name})
                });
            },
            completeNameEvent() {
                return this.autocomplete.event.entries.map(e => {
                    const name = e.name;
                    return Object.assign({}, e, {name})
                });
            },
        },

        watch: {
            'autocomplete.event.search': function (val) {
                window.methodSearch(val, this.completeNameEvent, this.autocomplete.event,
                    '/api/v2/events/list?api_token=' + window.auth.token + '' +
                    '&paginator=false&where=' + JSON.stringify([
                        ['name', 'like', val]
                    ]));
            },
            'autocomplete.channel.search': function (val) {
                window.methodSearch(val, this.completeNameChannel, this.autocomplete.channel,
                    '/api/v2/channels/list?api_token=' + window.auth.token + '' +
                    '&paginator=false&where=' + JSON.stringify([
                        ['name', 'like', val]
                    ]));
            },

        },

        created() {
            window.fetchAutocomplete('/api/v2/channels/list?api_token=' + window.auth.token + '' +
                '&paginator=false', this.autocomplete.channel);
            window.fetchAutocomplete('/api/v2/events/list?api_token=' + window.auth.token + '' +
                '&paginator=false', this.autocomplete.event);
        }
    }
</script>

<style scoped>
</style>
