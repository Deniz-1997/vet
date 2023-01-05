<template>
    <v-app class="body-column">
        <table-curd v-bind:api="table.events.template.api"
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
                                            :search-input.sync="eventSearch" color="black"
                                            hide-selected item-text="name"
                                            item-value="id" label="Событие" placeholder="Поиск события"
                                            v-model="slotProps.edit.event_id"></v-autocomplete>
                        </v-col>
                        <v-col cols="12">
                            <v-autocomplete :items="completeTemplate" :loading="autocomplete.template.isLoading"
                                            :search-input.sync="autocomplete.template.search" color="black"
                                            hide-selected item-text="name"
                                            item-value="id" label="Шаблон" placeholder="Поиск шаблона"
                                            v-model="slotProps.edit.template_id"></v-autocomplete>
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
                eventSearch:null,
                table: table,
                autocomplete: {
                    event: {start_loading_entries: false, isLoading: false, entries: [], search:null},
                    template: {start_loading_entries: false, isLoading: false, entries: [], search:null}
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
            completeTemplate() {
                return this.autocomplete.template.entries.map(e => {
                    const name = e.name;
                    return Object.assign({}, e, {name})
                });
            },
            completeEvent() {
                return this.autocomplete.event.entries.map(e => {
                    const name = e.name;
                    return Object.assign({}, e, {name})
                });
            },
        },


        watch: {
            'autocomplete.template.search': function (val) {
                window.methodSearch(val, this.completeTemplate, this.autocomplete.template,
                    '/api/v2/template/list?api_token=' + window.auth.token + '' +
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
            window.fetchAutocomplete('/api/v2/template/list?api_token=' + window.auth.token + '&paginator=false&limit=10000',
                this.autocomplete.template);

            window.fetchAutocomplete('/api/v2/events/list?api_token=' + window.auth.token + '&paginator=false&limit=10000',
                this.autocomplete.event);
        }
    }
</script>

<style scoped>
</style>
